<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserGroupRepository extends \Blueways\BwGuild\Domain\Repository\UserGroupRepository
{
    public function findAllGroupsWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_groups');
        $qb->getRestrictions()->removeAll();

        return $qb->select('dkfz_id')
            ->from('fe_groups')
            ->where(
                $qb->expr()->neq('dkfz_id', $qb->createNamedParameter('', \PDO::PARAM_STR))
            )
            ->execute()
            ->fetchAllAssociative();
    }

    public function bulkInsertDkfzIds(array $dkfzIds, int $pid)
    {
        if (!count($dkfzIds)) {
            return;
        }

        $rows = array_map(function ($dkfzId) use ($pid) {
            return [
                $dkfzId,
                $dkfzId,
                $pid,
            ];
        }, $dkfzIds);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('fe_groups');
        $connection->bulkInsert(
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

    public function deleteByDkfzIds(array $dkfzIds)
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
