<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<\Xima\XmDkfzNetSite\Domain\Model\BeUser>
 */
class BeGroupRepository extends Repository
{
    /**
     * @return array<int, array{dkfz_id: string, uid: int}>
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllGroupsWithDkfzId(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $result = $qb->select('dkfz_id', 'uid')
            ->from('be_groups')
            ->where(
                $qb->expr()->neq('dkfz_id', $qb->createNamedParameter(''))
            )
            ->execute();

        if (!$result instanceof Result) {
            return [];
        }

        return $result->fetchAllAssociative();
    }

    /**
     * @param array<string|int> $dkfzIds
     * @param string $subgroup
     * @return int
     */
    public function bulkInsertDkfzIds(array $dkfzIds, string $subgroup = ''): int
    {
        if (!count($dkfzIds)) {
            return 0;
        }

        $rows = array_map(function ($dkfzId) use ($subgroup) {
            return [
                (string)$dkfzId,
                (string)$dkfzId,
                $subgroup,
            ];
        }, $dkfzIds);

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('be_groups');
        return $connection->bulkInsert(
            'be_groups',
            $rows,
            [
                'dkfz_id',
                'title',
                'subgroup',
            ],
            [
                Connection::PARAM_STR,
                Connection::PARAM_STR,
                Connection::PARAM_STR,
            ]
        );
    }

    /**
     * @param array<string|int> $dkfzIds
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function deleteByDkfzIds(array $dkfzIds): int
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('be_groups');
        $qb->getRestrictions()->removeAll();

        $idStringList = array_map(function ($id) use ($qb) {
            return $qb->createNamedParameter($id);
        }, $dkfzIds);

        return $qb->delete('be_groups')
            ->where(
                $qb->expr()->in('dkfz_id', $idStringList)
            )
            ->executeStatement();
    }


}
