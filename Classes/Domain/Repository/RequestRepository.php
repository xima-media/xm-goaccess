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
}