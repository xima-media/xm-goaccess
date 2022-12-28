<?php

namespace Xima\XmDkfzNetSite\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class NewsRepository extends \GeorgRinger\News\Domain\Repository\NewsRepository
{
    /**
     * @throws Exception
     * @throws DBALException
     */
    public function getPreviewSysFileReferences(array $newsUids): array
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $result = $qb->select('n.uid as news', 'r.uid as file')
            ->from('sys_file_reference', 'r')
            ->join('r', 'tx_news_domain_model_news', 'n', $qb->expr()->eq('r.uid_foreign', $qb->quoteIdentifier('n.uid')))
            ->where(
                $qb->expr()->in('n.uid', $qb->quoteArrayBasedValueListToIntegerList($newsUids))
            )
            ->andWhere(
                $qb->expr()->eq('r.tablenames', $qb->createNamedParameter('tx_news_domain_model_news'))
            )
            ->execute();

        return $result->fetchAllKeyValue();
    }
}
