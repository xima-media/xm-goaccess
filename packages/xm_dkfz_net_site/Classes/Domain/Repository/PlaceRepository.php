<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry;

class PlaceRepository extends Repository implements ImportableUserInterface
{
    /**
     * @return array<int, array{dkfz_id: string, dkfz_hash: string}>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllUsersWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'dkfz_hash')
            ->from('tx_xmdkfznetsite_domain_model_place')
            ->where(
                $qb->expr()->neq('dkfz_id', 0)
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
                $user->nachname,
                $user->getUsergroup(),
                $user->raum,
                $pid,
            ];
        }, $entries);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xmdkfznetsite_domain_model_place');
        return $connection->bulkInsert(
            'tx_xmdkfznetsite_domain_model_place',
            $rows,
            [
                'dkfz_hash',
                'hidden',
                'dkfz_id',
                'name',
                'usergroup',
                'room',
                'pid',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_BOOL,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
            ]
        );
    }

    public function updateUserFromPhoneBookEntry(PhoneBookEntry $entry): int
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_xmdkfznetsite_domain_model_place')
            ->update(
                'tx_xmdkfznetsite_domain_model_place',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'disable' => $entry->getDisable(),
                    'name' => $entry->nachname,
                    'usergroup' => $entry->getUsergroup(),
                    'room' => $entry->raum,
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
                ]
            );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function deleteUsersByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_place');
        $qb->getRestrictions()->removeAll();

        return $qb->delete('tx_xmdkfznetsite_domain_model_place')
            ->where(
                $qb->expr()->in('dkfz_id', $dkfzIds)
            )
            ->executeStatement();
    }
}
