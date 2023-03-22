<?php

namespace Xima\XmGoaccess\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MappingRepository extends Repository
{
    public function getIgnoredPaths(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_mapping');
        $mappings = $qb->select('path', 'regex')
            ->from('tx_xmgoaccess_mapping')
            ->where($qb->expr()->eq('record_type', $qb->createNamedParameter(2, \PDO::PARAM_INT)))
            ->execute();

        return $mappings->fetchAllAssociative();
    }
}