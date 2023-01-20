<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;

class UserGroupRepository extends \Blueways\BwGuild\Domain\Repository\UserGroupRepository implements ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_number: string, uid: int, dkfz_hash: string}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllGroupsWithDkfzNumber(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_number', 'uid', 'dkfz_hash')
            ->from('fe_groups')
            ->where(
                $qb->expr()->neq('dkfz_number', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function bulkInsertPhoneBookAbteilungen(array $phoneBookAbteilungen, int $pid, array $fileMounts): int
    {
        if (!count($phoneBookAbteilungen)) {
            return 0;
        }

        $currentDate = (new DateTime())->getTimestamp();

        $rows = array_map(function ($abteilung) use ($pid, $currentDate) {
            return [
                $abteilung->nummer,
                $abteilung->bezeichnung,
                $pid,
                $abteilung->getHash(),
                $abteilung->managers,
                $abteilung->secretaries,
                $currentDate,
                $currentDate,
            ];
        }, $phoneBookAbteilungen);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_groups');
        return $connection->bulkInsert(
            'fe_groups',
            $rows,
            [
                'dkfz_number',
                'title',
                'pid',
                'dkfz_hash',
                'managers',
                'secretaries',
                'crdate',
                'tstamp',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
                Connection::PARAM_INT,
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function deleteByDkfzNumbers(array $dkfzNumbers): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        $idStringList = array_map(function ($id) use ($qb) {
            return $qb->createNamedParameter($id, \PDO::PARAM_STR);
        }, $dkfzNumbers);

        return $qb->delete('fe_groups')
            ->where(
                $qb->expr()->in('dkfz_number', $idStringList)
            )
            ->executeStatement();
    }

    public function findAllFileMounts(): array
    {
        return [];
    }

    public function bulkInsertFileMounts(array $dkfzNumbers, string $basePath): int
    {
        return 0;
    }

    public function updateFromPhoneBookEntry(PhoneBookAbteilung $entry): int
    {
        $currentDate = (new DateTime())->getTimestamp();

        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('fe_groups')
            ->update(
                'fe_groups',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'secretaries' => $entry->secretaries,
                    'managers' => $entry->managers,
                    'title' => $entry->bezeichnung,
                    'deleted' => 0,
                    'hidden' => 0,
                    'tstamp' => $currentDate,
                ],
                ['dkfz_number' => $entry->nummer],
                [
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_INT,
                ]
            );
    }
}
