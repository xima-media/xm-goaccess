<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;

class UserRepository extends \Blueways\BwGuild\Domain\Repository\UserRepository implements ImportableUserInterface
{
    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\DBALException
     * @return array<int, array{dkfz_id: string, dkfz_hash: string}>
     */
    public function findAllUsersWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash')
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
     * @param PhoneBookPerson[] $persons
     */
    public function bulkInsertFromPhoneBook(array $persons, int $pid): int
    {
        if (!count($persons)) {
            return 0;
        }

        $rows = array_map(function ($person) use ($pid) {
            return [
                $person->dkfzHash,
                $person->disable,
                $person->dkfzId,
                $person->firstName,
                $person->title,
                $person->lastName,
                $person->email,
                $person->adAccountName,
                $person->username,
                $person->gender,
                $person->usergroup,
                $pid,
            ];
        }, $persons);

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
            ]
        );
    }

    public function updateUserFromPhoneBook(PhoneBookPerson $person): int
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('fe_users')
            ->update(
                'fe_users',
                [
                    'dkfz_hash' => $person->dkfzHash,
                    'disable' => $person->disable,
                    'first_name' => $person->firstName,
                    'title' => $person->title,
                    'last_name' => $person->lastName,
                    'email' => $person->email,
                    'ad_account_name' => $person->adAccountName,
                    'username' => $person->username,
                    'gender' => $person->gender,
                    'usergroup' => $person->usergroup,
                ],
                ['dkfz_id' => $person->dkfzId],
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
                ]
            );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function deleteUserByDkfzIds(array $ids): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        return $qb->delete('fe_users')
            ->where(
                $qb->expr()->in('dkfz_id', $ids)
            )
            ->executeStatement();
    }
}
