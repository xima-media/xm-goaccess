<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Xima\XmDkfzNetSite\Domain\Model\BeUser;

/**
 * @extends Repository<BeUser>
 */
class BeGroupRepository extends Repository implements ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_id: string, uid: int}>
     * @throws DBALException
     * @throws Exception
     */
    public function findAllGroupsWithDkfzNumber(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_number', 'uid')
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

        $rows = array_map(function ($abteilung) use ($fileMounts) {
            $mountPoints = array_filter($fileMounts, function ($fileMount) use ($abteilung) {
                return $fileMount['title'] === $abteilung->nummer;
            });
            $mountPoints = array_map(function ($mountPoint) {
                return $mountPoint['uid'];
            }, $mountPoints);
            $mountPoints = implode(',', $mountPoints);

            return [
                $abteilung->nummer,
                $abteilung->bezeichnung,
                $mountPoints,
            ];
        }, $phoneBookAbteilungen);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_groups');
        return $connection->bulkInsert(
            'be_groups',
            $rows,
            [
                'dkfz_number',
                'title',
                'file_mountpoints',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
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
}
