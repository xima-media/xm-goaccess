<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserGroupRepository extends \Blueways\BwGuild\Domain\Repository\UserGroupRepository implements ImportableGroupInterface
{
    /**
     * @return array<int, array{dkfz_id: string, uid: int}>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllGroupsWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'uid')
            ->from('fe_groups')
            ->where(
                $qb->expr()->neq('dkfz_id', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    public function bulkInsertDkfzIds(array $dkfzIds, int $pid, string $subgroup): int
    {
        if (!count($dkfzIds)) {
            return 0;
        }

        $rows = array_map(function ($dkfzId) use ($pid) {
            return [
                $dkfzId,
                $dkfzId,
                $pid,
            ];
        }, $dkfzIds);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_groups');
        return $connection->bulkInsert(
            'fe_groups',
            $rows,
            [
                'dkfz_id',
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
    public function deleteByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        $idStringList = array_map(function ($id) use ($qb) {
            return $qb->createNamedParameter($id, \PDO::PARAM_STR);
        }, $dkfzIds);

        return $qb->delete('fe_groups')
            ->where(
                $qb->expr()->in('dkfz_id', $idStringList)
            )
            ->executeStatement();
    }
}
