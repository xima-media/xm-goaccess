<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;

class UserRepository extends \Blueways\BwGuild\Domain\Repository\UserRepository implements ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: string, dkfz_hash: string, uid: int}>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
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

        $rows = array_map(function ($user) use ($pid) {
            return [
                $user->getHash(),
                $user->getDisable(),
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
                $pid,
            ];
        }, $entries);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');
        return $connection->bulkInsert(
            'fe_users',
            $rows,
            [
                'dkfz_hash',
                'disable',
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
                Connection::PARAM_STR,
                Connection::PARAM_INT,
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
     * @throws \Doctrine\DBAL\DBALException
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
}
