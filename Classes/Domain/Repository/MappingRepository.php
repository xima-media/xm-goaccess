<?php

namespace Xima\XmGoaccess\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MappingRepository extends Repository
{
    public function getIgnoredPaths(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_domain_model_mapping');
        $mappings = $qb->select('path', 'regex')
            ->from('tx_xmgoaccess_domain_model_mapping')
            ->where($qb->expr()->eq('record_type', $qb->createNamedParameter(2, \PDO::PARAM_INT)))
            ->execute();

        return $mappings->fetchAllAssociative();
    }

    /**
     * @return string[]
     * @throws DBALException
     * @throws Exception
     */
    public function getAllNonRegexPaths(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_domain_model_mapping');
        $result = $qb->select('path')
            ->from('tx_xmgoaccess_domain_model_mapping')
            ->where($qb->expr()->eq('regex', $qb->createNamedParameter(0, \PDO::PARAM_INT)))
            ->execute();

        $mappings = $result->fetchAllAssociative();

        return array_column($mappings, 'path');
    }

    public function getAllPageMappings(): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_xmgoaccess_domain_model_mapping');
        $result = $qb->select('page', 'path')
            ->from('tx_xmgoaccess_domain_model_mapping')
            ->where($qb->expr()->eq('record_type', $qb->createNamedParameter(0, \PDO::PARAM_INT)))
            ->execute();

        return $result->fetchAllAssociative();
    }

}