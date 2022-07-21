<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookPerson;

class UserRepository extends \Blueways\BwGuild\Domain\Repository\UserRepository
{
    public function findAllDkfzUser(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $qb->getRestrictions()->removeAll();

        return $qb->select('dkfz_id', 'dkfz_hash')
            ->from('fe_users')
            ->where(
                $qb->expr()->neq('ad_account_name', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute()
            ->fetchAllAssociative();
    }

    /**
     * @param PhoneBookPerson[] $persons
     */
    public function bulkInsertFromPhoneBook(array $persons)
    {
        if (!count($persons)) {
            return;
        }

        $rows = array_map(function ($person) {
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
            ];
        }, $persons);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_users');
        $connection->bulkInsert(
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
            ]
        );
    }

    public function updateFromPhoneBook(PhoneBookPerson $person)
    {
        GeneralUtility::makeInstance(ConnectionPool::class)
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
                ]
            );
    }

    public function deleteByDkfzIds(array $ids)
    {

    }
}
