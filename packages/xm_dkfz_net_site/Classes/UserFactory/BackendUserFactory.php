<?php

namespace Xima\XmDkfzNetSite\UserFactory;

use JetBrains\PhpStorm\ArrayShape;
use TYPO3\CMS\Core\Authentication\CommandLineUserAuthentication;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Waldhacker\Oauth2Client\Repository\BackendUserRepository;
use Xima\XmDkfzNetSite\ResourceResolver\AbstractResolver;

class BackendUserFactory
{
    private const OAUTH2_BE_CONFIG_TABLE = 'tx_oauth2_beuser_provider_configuration';

    protected DataHandler $dataHandler;

    protected AbstractResolver $resolver;

    protected BackendUserRepository $backendUserRepository;

    /**
     * @param \Xima\XmDkfzNetSite\ResourceResolver\AbstractResolver $resolver
     */
    public function setResolver(AbstractResolver $resolver): void
    {
        $this->resolver = $resolver;
    }

    protected QueryBuilder $queryBuilder;

    /**
     * @param \TYPO3\CMS\Core\Database\Query\QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder, BackendUserRepository $backendUserRepository, DataHandler $dataHandler)
    {
        $queryBuilder->getRestrictions()
            ->removeAll()
            ->add(new DeletedRestriction());

        $this->queryBuilder = $queryBuilder;
        $this->backendUserRepository = $backendUserRepository;
        $this->dataHandler = $dataHandler;
    }

    protected function findUserByUsernameOrEmail()
    {
        $constraints = [];
        $username = $this->resolver->getIntendedUsername();
        $email = $this->resolver->getIntendedEmail();

        if ($username) {
            $constraints[] = $this->queryBuilder->expr()->eq(
                'username',
                $this->queryBuilder->createNamedParameter($username, \PDO::PARAM_STR)
            );
        }

        if ($email) {
            $constraints[] = $this->queryBuilder->expr()->eq(
                'email',
                $this->queryBuilder->createNamedParameter($email, \PDO::PARAM_STR)
            );
        }

        if (empty($constraints)) {
            return null;
        }

        return $this->queryBuilder
            ->select('*')
            ->from('be_users')
            ->where($this->queryBuilder->expr()->orX(...$constraints))
            ->execute()
            ->fetch();
    }

    public function registerRemoteUser(): ?array
    {
        // find or create
        $userRecord = $this->findUserByUsernameOrEmail() ?? $this->createBasicBackendUser();

        // update
        $this->resolver->updateBackendUser($userRecord);

        if (!$userRecord['username']) {
            return null;
        }

        if (!isset($userRecord['uid'])) {
            $userRecord = $this->persistAndRetrieveUser($userRecord);
        }

        // TODO: test user

        $this->persistIdentityForUser($userRecord);



        return null;
    }

    public function connectGitlabToBeUser(): void
    {
    }

    public function persistIdentityForUser($userRecord)
    {
        \TYPO3\CMS\Core\Core\Bootstrap::initializeBackendUser(CommandLineUserAuthentication::class);

        $data = [
            'be_users' => [
                $userRecord['uid'] => [
                    'tx_oauth2_client_configs' => 'NEW12345',
                ],
            ],
            self::OAUTH2_BE_CONFIG_TABLE => [
                'NEW12345' => [

                ],
            ],
        ];

        $identity = $this->queryBuilder->insert(self::OAUTH2_BE_CONFIG_TABLE)
            ->values([
                'identifier' => $this->resolver->getResourceOwner()->getId(),
                'provider' => $this->resolver->getProviderId(),
            ])
            ->execute()
            ->fetchFirstColumn();

        $this->dataHandler->start($data, []);
        $this->dataHandler->process_datamap();

        if (!empty($this->dataHandler->errorLog)) {

            $err = $this->dataHandler->errorLog;
        }
    }

    public function persistAndRetrieveUser($userRecord)
    {
        $password = $userRecord['password'];

        $user = $this->queryBuilder->insert('be_users')
            ->values($userRecord)
            ->execute();

        if (!$user) {
            return null;
        }

        return $this->queryBuilder->select('*')->from('be_users')->where(
            $this->queryBuilder->expr()->eq('password', $password)
        )->execute()->fetchFirstColumn();
    }

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
        $saltingInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');

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
}
