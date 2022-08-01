<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<\Xima\XmDkfzNetSite\Domain\Model\Contact>
 */
class ContactRepository extends Repository
{
    /**
     * @param array<\Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookEntry> $entries
     * @param int $pid
     * @param array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}> $dbUsers
     * @return int
     */
    public function bulkInsertPhoneBookEntries(array $entries, int $pid, array $dbUsers): int
    {
        if (!count($entries)) {
            return 0;
        }

        $rows = [];

        $dbUserUidsById = [];
        foreach ($dbUsers as $dbUser) {
            $dbUserUidsById[$dbUser['dkfz_id']] = $dbUser['uid'];
        }

        foreach ($entries as $entry) {
            if (!isset($dbUserUidsById[$entry->id])) {
                continue;
            }

            $foreignUid = $dbUserUidsById[$entry->id];
            $foreignTable = $entry->isUser() ? 'fe_users' : 'tx_xmdkfznetsite_domain_model_place';

            foreach ($entry->rufnummern as $key => $rufnummer) {
                $rows[] = [
                    $foreignUid,
                    $foreignTable,
                    $rufnummer->getRecordType(),
                    $rufnummer->rufnummer,
                    $rufnummer->raum,
                    ($key + 1),
                    $rufnummer->feGroup,
                    $rufnummer->funktion,
                    $rufnummer->primaernummer,
                    $pid,
                ];
            }
        }

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xmdkfznetsite_domain_model_contact');
        return $connection->bulkInsert(
            'tx_xmdkfznetsite_domain_model_contact',
            $rows,
            [
                'foreign_uid',
                'foreign_table',
                'record_type',
                'number',
                'room',
                'sorting',
                'fe_group',
                'function',
                'primary_number',
                'pid',
            ],
            [
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_BOOL,
                Connection::PARAM_INT,
            ]
        );
    }

    /**
     * @param array<int, array{dkfz_id: int, dkfz_hash: string, uid: int}> $dbUsers
     * @param string $foreignTable
     * @return int
     */
    public function deleteByDkfzUserIds(array $dbUsers, string $foreignTable): int
    {
        $userUidList = array_map(function ($dbUser) {
            return $dbUser['uid'];
        }, $dbUsers);

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_xmdkfznetsite_domain_model_contact');

        return $qb->delete('tx_xmdkfznetsite_domain_model_contact')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('foreign_table', $qb->createNamedParameter($foreignTable, \PDO::PARAM_STR)),
                    $qb->expr()->in('foreign_uid', $userUidList)
                )
            )
            ->executeStatement();
    }
}
