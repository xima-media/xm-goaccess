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

        foreach ($entries as $entry) {
            $dkfzId = $entry->id;
            $dbUser = array_filter($dbUsers, function ($dbUser) use ($dkfzId) {
                return $dbUser['dkfz_id'] === $dkfzId;
            });

            if (count($dbUser) !== 1) {
                continue;
            }

            $foreignUid = array_pop($dbUser)['uid'];
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
}
