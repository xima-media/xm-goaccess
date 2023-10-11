<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XmDkfzNetSite\Domain\Model\BeUser;
use Xima\XmDkfzNetSite\Domain\Model\Dto\PhoneBookAbteilung;

/**
 * @extends Repository<BeUser>
 */
class BeGroupRepository extends Repository implements ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_id: string, uid: int, dkfz_hash: string}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllGroupsWithDkfzNumber(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_number', 'uid', 'dkfz_hash')
            ->from('be_groups')
            ->where(
                $qb->expr()->neq('dkfz_number', $qb->createNamedParameter(''))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function bulkInsertPhoneBookAbteilungen(
        array $phoneBookAbteilungen,
        int $pid,
        array $fileMounts
    ): int {
        if (!count($phoneBookAbteilungen)) {
            return 0;
        }

        $currentDate = (new DateTime())->getTimestamp();

        $rows = array_map(function ($abteilung) use ($fileMounts, $currentDate) {
            $mountPoints = array_filter($fileMounts, function ($fileMount) use ($abteilung) {
                return $fileMount['title'] === $abteilung->nummer;
            });
            $mountPoints = array_map(function ($mountPoint) {
                return $mountPoint['uid'];
            }, $mountPoints);
            $mountPoints = implode(',', $mountPoints);

            return [
                $abteilung->nummer,
                $abteilung->getUniqueIdentifier(),
                $abteilung->bezeichnung,
                $abteilung->managers,
                $abteilung->secretaries,
                $abteilung->coordinators,
                $abteilung->assistants,
                $mountPoints,
                $abteilung->getHash(),
                $currentDate,
                $currentDate,
            ];
        }, $phoneBookAbteilungen);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_groups');
        return $connection->bulkInsert(
            'be_groups',
            $rows,
            [
                'dkfz_number',
                'dkfz_group_identifier',
                'title',
                'managers',
                'secretaries',
                'coordinators',
                'assistants',
                'file_mountpoints',
                'dkfz_hash',
                'crdate',
                'tstamp',
            ],
            [
                Connection::PARAM_STR,
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

    /**
     * @param array<string> $dkfzNumbers
     * @return int
     * @throws DBALException
     */
    public function deleteByDkfzNumbers(array $dkfzNumbers): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $idStringList = array_map(function ($id) use ($qb) {
            return $qb->createNamedParameter($id);
        }, $dkfzNumbers);

        return $qb->delete('be_groups')
            ->where(
                $qb->expr()->in('dkfz_number', $idStringList)
            )
            ->executeStatement();
    }

    /**
     * @return array<int, array{title: string, uid: int}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllFileMounts(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_filemounts');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('uid', 'title')
            ->from('sys_filemounts')
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function bulkInsertFileMounts(array $dkfzNumbers, string $basePath): int
    {
        if (!count($dkfzNumbers)) {
            return 0;
        }

        $rows = array_map(function ($numberToCreate) use ($basePath) {
            return [
                $numberToCreate,
                '/' . $basePath . '/' . $numberToCreate . '/',
                1,
            ];
        }, $dkfzNumbers);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_filemounts');
        return $connection->bulkInsert(
            'sys_filemounts',
            $rows,
            [
                'title',
                'path',
                'base',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
            ]
        );
    }

    public function updateFromPhoneBookEntry(PhoneBookAbteilung $entry): int
    {
        $currentDate = (new DateTime())->getTimestamp();

        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('be_groups')
            ->update(
                'be_groups',
                [
                    'dkfz_hash' => $entry->getHash(),
                    'dkfz_group_identifier' => $entry->getUniqueIdentifier(),
                    'title' => $entry->bezeichnung,
                    'managers' => $entry->managers,
                    'secretaries' => $entry->secretaries,
                    'coordinators' => $entry->coordinators,
                    'assistants' => $entry->assistants,
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
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_STR,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_BOOL,
                    Connection::PARAM_INT,
                ]
            );
    }

    /**
     * @throws Exception
     * @throws DBALException
     */
    public function findAllGroupsWithoutShortNewsMountPoint(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('g.dkfz_number', 'g.uid as group_uid', 'g.db_mountpoints')
            ->from('be_groups', 'g')
            ->leftJoin(
                'g',
                'pages',
                'p',
                $qb->expr()->andX(
                    $qb->expr()->inSet('g.db_mountpoints', $qb->quoteIdentifier('p.uid')),
                    $qb->expr()->eq('p.title', 'CONCAT("Kurzmeldungen (", ' . $qb->quoteIdentifier('g.dkfz_number') . ', ")")'),
                    $qb->expr()->eq('p.deleted', 0)
                )
            )
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->neq('g.dkfz_number', $qb->createNamedParameter('')),
                    $qb->expr()->isNull('p.title')
                )
            )
            ->groupBy('g.uid')
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }
}
