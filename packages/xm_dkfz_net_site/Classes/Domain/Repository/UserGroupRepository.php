<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserGroupRepository extends \Blueways\BwGuild\Domain\Repository\UserGroupRepository implements ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_number: string, uid: int}>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllGroupsWithDkfzNumber(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_number', 'uid')
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

    public function bulkInsertPhoneBookAbteilungen(array $phoneBookAbteilungen, int $pid, string $subgroup, array $fileMounts): int
    {
        if (!count($phoneBookAbteilungen)) {
            return 0;
        }

        $rows = array_map(function ($abteilung) use ($pid) {
            return [
                $abteilung->nummer,
                $abteilung->bezeichnung,
                $pid,
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
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_INT,
            ]
        );
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
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
}
