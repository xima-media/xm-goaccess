<?php

namespace Xima\XmGoaccess\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class RequestRepository extends Repository
{
    public function getAllDates(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_domain_model_request');
        $query = $qb->select('date')
            ->from('tx_xmgoaccess_domain_model_request')
            ->groupBy('date')
            ->execute();

        $result = $query->fetchAllAssociative();

        return array_column($result, 'date');
    }

    public function getChartDataForPage(int $pageUid): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_domain_model_request');
        $query = $qb->select('date', 'hits', 'visitors')
            ->from('tx_xmgoaccess_domain_model_request')
            ->where($qb->expr()->eq('page', $qb->createNamedParameter($pageUid, \PDO::PARAM_INT)))
            ->groupBy('page', 'date')
            ->execute();

        return $query->fetchAllAssociative();
    }
}