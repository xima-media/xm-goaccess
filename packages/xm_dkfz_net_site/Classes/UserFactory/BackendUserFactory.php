<?php

namespace Xima\XmDkfzNetSite\UserFactory;

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException;
use Doctrine\DBAL\Driver\Exception;
use JetBrains\PhpStorm\ArrayShape;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Waldhacker\Oauth2Client\Database\Query\Restriction\Oauth2BeUserProviderConfigurationRestriction;
use Xima\XmDkfzNetSite\ResourceResolver\AbstractResolver;

class BackendUserFactory
{
    protected AbstractResolver $resolver;

    /**
     * @param AbstractResolver $resolver
     */
    public function setResolver(AbstractResolver $resolver): void
    {
        $this->resolver = $resolver;
    }

    protected function findUserByUsernameOrEmail(): ?array
    {
        $constraints = [];
        $username = $this->resolver->getIntendedUsername();
        $email = $this->resolver->getIntendedEmail();
        $qb = $this->getQueryBuilder();

        if ($username) {
            $constraints[] = $qb->expr()->eq(
                'username',
                $qb->createNamedParameter($username, \PDO::PARAM_STR)
            );
        }

        if ($email) {
            $constraints[] = $qb->expr()->eq(
                'email',
                $qb->createNamedParameter($email, \PDO::PARAM_STR)
            );
        }

        if (empty($constraints)) {
            return null;
        }

        $user = $qb
            ->select('*')
            ->from('be_users')
            ->where($qb->expr()->orX(...$constraints))
            ->execute()
            ->fetchAssociative();

        return $user ?: null;
    }

    public function registerRemoteUser(): ?array
    {
        // find or create
        $userRecord = $this->findUserByUsernameOrEmail();
        if (!is_array($userRecord)) {
            $userRecord = $this->createBasicBackendUser();
        }

        // update
        $this->resolver->updateBackendUser($userRecord);

        // test for username
        if (!$userRecord['username']) {
            return null;
        }

        // test for persistence
        if (!isset($userRecord['uid'])) {
            $userRecord = $this->persistAndRetrieveUser($userRecord);
        }

        try {
            if ($this->persistIdentityForUser($userRecord)) {
                return $userRecord;
            }
        } catch (Exception $e) {
        }

        return null;
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    public function persistIdentityForUser($userRecord): bool
    {
        // create identity
        $qb = $this->getQueryBuilder('tx_oauth2_beuser_provider_configuration');
        $qb->insert('tx_oauth2_beuser_provider_configuration')
            ->values([
                'identifier' => $this->resolver->getResourceOwner()->getId(),
                'provider' => $this->resolver->getProviderId(),
                'crdate' => time(),
                'tstamp' => time(),
                'cruser_id' => (int)$userRecord['uid'],
                'parentid' => (int)$userRecord['uid'],
            ])
            ->execute();

        // get newly created identity
        $qb = $this->getQueryBuilder('tx_oauth2_beuser_provider_configuration');
        $qb->getRestrictions()->removeByType(Oauth2BeUserProviderConfigurationRestriction::class);
        $identityCount = $qb->count('uid')
            ->from('tx_oauth2_beuser_provider_configuration')
            ->where($qb->expr()->eq('parentid', (int)$userRecord['uid']))
            ->executeQuery()
            ->fetchOne();

        if (!$identityCount > 0) {
            return false;
        }

        // update backend user
        $qb = $this->getQueryBuilder();
        $qb->update('be_users')
            ->where(
                $qb->expr()->eq('uid', (int)$userRecord['uid'])
            )
            ->set('tx_oauth2_client_configs', (int)$identityCount)
            ->executeStatement();

        return true;
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    public function persistAndRetrieveUser($userRecord): ?array
    {
        $password = $userRecord['password'];

        $user = $this->getQueryBuilder()->insert('be_users')
            ->values($userRecord)
            ->execute();

        if (!$user) {
            return null;
        }

        $qb = $this->getQueryBuilder();
        return $qb->select('*')
            ->from('be_users')
            ->where(
                $qb->expr()->eq('password', $qb->createNamedParameter($password, \PDO::PARAM_STR))
            )
            ->execute()
            ->fetchAssociative();
    }

    /**
     * @throws InvalidPasswordHashException
     */
    /**
     * @throws InvalidPasswordHashException
     */
    #[ArrayShape([
        'username' => 'string',
        'realName' => 'string',
        'disable' => 'int',
        'crdate' => 'int',
        'tstamp' => 'int',
        'admin' => 'int',
        'starttime' => 'int',
        'endtime' => 'int',
        'password' => 'string',
    ])] public function createBasicBackendUser(): array
    {
        $saltingInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('BE');

        return [
            'crdate' => time(),
            'tstamp' => time(),
            'admin' => 0,
            'disable' => 1,
            'starttime' => 0,
            'endtime' => 0,
            'password' => $saltingInstance->getHashedPassword(md5(uniqid())),
            'realName' => '',
            'username' => '',
        ];
    }

    protected function getQueryBuilder($tableName = 'be_users'): QueryBuilder
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);
        $qb->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        return $qb;
    }
}
