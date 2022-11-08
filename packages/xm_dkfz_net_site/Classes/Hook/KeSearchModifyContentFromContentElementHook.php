<?php

declare(strict_types=1);

namespace Xima\XmDkfzNetSite\Hook;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Result;
use PDO;
use Tpwd\KeSearch\Indexer\Types\Page;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KeSearchModifyContentFromContentElementHook
{
    protected const TABLENAME_TT_CONTENT_ITEMS = 'tt_content_item';

    public function modifyPageContentFields(
        string &$fields,
        Page $parentObject
    ): void {
        $fields .= ',tt_content_items';
    }

    /**
     * @throws Exception
     * @throws DBALException
     */
    public function modifyContentFromContentElement(
        string &$content,
        array $ttContentRow,
        Page $parentObject
    ): void {
        if ($this->isValidTtContentRow($ttContentRow)) {
            $content = $this->addTtContentItemContent($ttContentRow['uid'], $content);
        }
    }

    protected function isValidTtContentRow(array $ttContentRow): bool
    {
        return isset($ttContentRow['uid'], $ttContentRow['tt_content_items']) && $ttContentRow['tt_content_items'] > 0;
    }

    /**
     * @param int $uid
     * @param string $content
     * @param string $foreignTable
     * @return string
     * @throws DBALException
     * @throws Exception
     */
    protected function addTtContentItemContent(int $uid, string $content, string $foreignTable = 'tt_content'): string
    {
        $result = $this->getTtContentItemResultForForeignTable($uid, $foreignTable);

        while ($row = $result->fetchAssociative()) {
            $content .= PHP_EOL . $row['title'] . PHP_EOL . $row['text'];
            if ($this->ttContentItemHasChildren($row['uid'])) {
                $content = $this->addTtContentItemContent($row['uid'], $content, self::TABLENAME_TT_CONTENT_ITEMS);
            }
        }

        return $content;
    }

    /**
     * @param int $uid
     * @param string $foreignTable
     * @return Result
     * @throws DBALException
     */
    protected function getTtContentItemResultForForeignTable(
        int $uid,
        string $foreignTable
    ): Result {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLENAME_TT_CONTENT_ITEMS);

        return $queryBuilder->select('uid', 'title', 'text')
            ->from(self::TABLENAME_TT_CONTENT_ITEMS)
            ->where(
                $queryBuilder->expr()->eq('foreign_table', $queryBuilder->createNamedParameter($foreignTable)),
                $queryBuilder->expr()->eq('foreign_uid', value: $queryBuilder->createNamedParameter(
                    $uid,
                    PDO::PARAM_INT
                ))
            )
            ->executeQuery();
    }

    /**
     * @throws DBALException
     * @throws Exception
     */
    protected function ttContentItemHasChildren(int $uid): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLENAME_TT_CONTENT_ITEMS);

        $result = $queryBuilder->count('uid')
            ->from(self::TABLENAME_TT_CONTENT_ITEMS)
            ->where(
                $queryBuilder->expr()->eq(
                    'foreign_table',
                    $queryBuilder->createNamedParameter(self::TABLENAME_TT_CONTENT_ITEMS)
                ),
                $queryBuilder->expr()->eq('foreign_uid', $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT))
            )
            ->executeQuery()
            ->fetchOne();

        return (int)$result > 0;
    }
}
