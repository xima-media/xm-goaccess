<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;

/**
 * @extends Repository<\Xima\XmDkfzNetSite\Domain\Model\BeUser>
 */
class BeUserRepository extends Repository implements ImportableUserInterface
{
    public function findByDkfzIds(array $dkfzIds): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_users');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('be_users')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function findAllUsersWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_users');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash', 'uid')
            ->from('be_users')
            ->where(
                $qb->expr()->neq('ad_account_name', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function bulkInsertPhoneBookEntries(array $entries, int $pid): int
    {
        if (!count($entries)) {
            return 0;
        }

        $saltingInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('BE');
        $defaultPassword = $saltingInstance->getHashedPassword(md5(uniqid()));

        $rows = array_map(function ($user) use ($defaultPassword) {
            return [
                $user->getHash(),
                1,
                $user->id,
                $user->getCombinedName(),
                $user->mail,
                $user->adAccountName,
                $user->getUsername(),
                $user->usergroup,
                $defaultPassword,
                3,
                0,
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
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_users');

        return $connection->bulkInsert(
            'be_users',
            $rows,
            [
                'dkfz_hash',
                'disable',
                'dkfz_id',
                'realName',
                'email',
                'ad_account_name',
                'username',
                'usergroup',
                'password',
                'options',
                'pid',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_BOOL,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_INT,
            ]
        );
    }

    public function updateUserFromPhoneBookEntry(PhoneBookEntry $entry): int
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('be_users')
            ->update(
                'be_users',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'realName' => $entry->getCombinedName(),
                    'email' => $entry->mail,
                    'ad_account_name' => $entry->adAccountName,
                    'username' => $entry->getUsername(),
                    'usergroup' => $entry->usergroup,
                ],
                ['dkfz_id' => $entry->id],
                [
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                ]
            );
    }

    public function deleteUsersByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_users');
        $qb->getRestrictions()->removeAll();

        return $qb->delete('be_users')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->executeStatement();
    }
}
