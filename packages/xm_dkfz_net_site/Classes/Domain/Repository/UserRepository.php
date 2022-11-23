<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;
use Xima\XmDkfzNetSite\Domain\Model\User;

class UserRepository extends \Blueways\BwGuild\Domain\Repository\UserRepository implements ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllUsersWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('fe_users')
            ->where(
                $qb->expr()->neq('ad_account_name', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    /**
     * @param PhoneBookEntry[] $entries
     */
    public function bulkInsertPhoneBookEntries(array $entries, int $pid): int
    {
        if (!count($entries)) {
            return 0;
        }

        $saltingInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $defaultPassword = $saltingInstance->getHashedPassword(md5(uniqid()));

        $rows = array_map(function ($user) use ($pid, $defaultPassword) {
            return [
                $user->getHash(),
                $user->id,
                $user->vorname,
                $user->titel,
                $user->nachname,
                $user->mail,
                $user->adAccountName,
                $user->getUsername(),
                $user->getGender(),
                $user->usergroup,
                count($user->rufnummern),
                $user->getUsername(),
                $defaultPassword,
                $pid,
            ];
        }, $entries);

        // chunk rows in order to prevent to reach the max. number of mysql placeholders (default 65,535)
        $insertCount = 0;
        $chunkedRowArrays = array_chunk($rows, 3000);
        foreach ($chunkedRowArrays as $chunkRow) {
            $insertCount += $this->bulkInsertRows($chunkRow);
        }

        return $insertCount;
    }

    /**
     * @param array<int, mixed> $rows
     * @return int
     */
    protected function bulkInsertRows(array $rows): int
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');

        return $connection->bulkInsert(
            'fe_users',
            $rows,
            [
                'dkfz_hash',
                'dkfz_id',
                'first_name',
                'title',
                'last_name',
                'email',
                'ad_account_name',
                'username',
                'gender',
                'usergroup',
                'contacts',
                'slug',
                'password',
                'pid',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
            ]
        );
    }

    public function updateUserFromPhoneBookEntry(PhoneBookEntry $entry): int
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('fe_users')
            ->update(
                'fe_users',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'disable' => $entry->getDisable(),
                    'deleted' => 0,
                    'first_name' => $entry->vorname,
                    'title' => $entry->titel,
                    'last_name' => $entry->nachname,
                    'email' => $entry->mail,
                    'ad_account_name' => $entry->adAccountName,
                    'username' => $entry->getUsername(),
                    'gender' => $entry->getGender(),
                    'usergroup' => $entry->usergroup,
                    'contacts' => count($entry->rufnummern),
                ],
                ['dkfz_id' => $entry->id],
                [
                    Connection::PARAM_STR,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_INT,
                    Connection::PARAM_STR,
                    Connection::PARAM_INT,
                ]
            );
    }

    /**
     * @throws DBALException
     */
    public function deleteUsersByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        return $qb->delete('fe_users')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->executeStatement();
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    public function findByDkfzIds(array $dkfzIds): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('fe_users')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    /**
     * @param array<int, int|string> $uids
     * @return object[]|QueryResultInterface<User>
     * @throws InvalidQueryException
     */
    public function findByUids(array $uids): array|QueryResultInterface
    {
        $query = $this->createQuery();
        $query->setQuerySettings($query->getQuerySettings()->setRespectStoragePage(false));
        $query->matching(
            $query->in('uid', $uids)
        );

        return $query->execute();
    }
}
