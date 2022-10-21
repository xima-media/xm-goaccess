<?php
namespace Tpwd\KeSearchPremium\Hooks;

use Exception;
use Tpwd\KeSearch\Indexer\Types\News as NewsIndexer;
use Tpwd\KeSearch\Indexer\Types\Page as PageIndexer;
use Tpwd\KeSearch\Lib\Db;

class BoostKeywords
{
    /**
     * Modifies the search query and takes the "boostkeywords" column into account
     *
     * @param array $queryParts
     * @param Db $caller
     * @param $searchwordQuoted
     * @return array
     * @throws Exception
     */
    public function getQueryParts(array $queryParts, Db $caller, $searchwordQuoted)
    {
        if ($caller->pObj->extConfPremium['enableBoostKeywords']) {
            if ($caller->pObj->sword) {
                $titleFactor = $caller->pObj->extConf['multiplyValueToTitle'] ?: 1;
                $boostKeywordsFactor = $caller->pObj->extConfPremium['boostKeywordsFactor'] ?: 5;

                $search = '(' . $titleFactor . ' * MATCH (title) AGAINST (' . $searchwordQuoted . '))';
                $replace =
                    $search
                    . ' + (' . $boostKeywordsFactor . ' * MATCH (boostkeywords) AGAINST (' . $searchwordQuoted . '))';

                $queryParts['SELECT'] = str_replace($search, $replace, $queryParts['SELECT']);
            }
        }
        return $queryParts;
    }

    /**
     * @param array $additionalFields
     */
    public function registerAdditionalFields(&$additionalFields)
    {
        $additionalFields[] = 'boostkeywords';
    }

    /**
     * Add keywords from pages as boostkeywords
     *
     * @param $uid
     * @param $pageContent
     * @param $tags
     * @param array $cachedPageRecords
     * @param array $additionalFields
     * @param $indexerConfig
     * @param $indexEntryDefaultValues
     * @param PageIndexer $caller
     */
    public function modifyPagesIndexEntry(
        $uid,
        $pageContent,
        $tags,
        array $cachedPageRecords,
        array &$additionalFields,
        $indexerConfig,
        $indexEntryDefaultValues,
        PageIndexer $caller
    )
    {
        if ($caller->pObj->extConfPremium['enableBoostKeywords']) {
            // Save a serialized array of the keywords of all languages because unfortunately we cannot
            // differentiate at this point for which language we want to set the additionalFields.
            $keywords = [];
            foreach ($cachedPageRecords as $language => $pageRecords) {
                if ($pageRecords[$uid]['keywords'] ?? '') {
                    $keywords[$language] = $pageRecords[$uid]['keywords'];
                }
            }
            if ($keywords) {
                $additionalFields['boostkeywords'] = serialize($keywords);
            }
        }
    }

    /**
     * @param $indexerConfig
     * @param array $fieldValues
     * @return array
     */
    public function modifyFieldValuesBeforeStoring($indexerConfig, array &$fieldValues)
    {
        if (
            $fieldValues['type'] == 'page' &&
            isset($fieldValues['boostkeywords'])
            && !empty($fieldValues['boostkeywords'])
        ) {
            $kewords = unserialize($fieldValues['boostkeywords']);
            $fieldValues['boostkeywords'] = $kewords[$fieldValues['language']];
        }
        return $fieldValues;
    }

    /**
     * Add keywords from news as boostkeywords
     *
     * @param $title
     * @param $abstract
     * @param $fullContent
     * @param $params
     * @param $tags
     * @param array $newsRecord
     * @param $additionalFields
     * @param $indexerConfig
     * @param $categoryData
     * @param NewsIndexer $caller
     */
    public function modifyExtNewsIndexEntry(
        $title,
        $abstract,
        $fullContent,
        $params,
        $tags,
        array $newsRecord,
        &$additionalFields,
        $indexerConfig,
        $categoryData,
        NewsIndexer $caller
    ) {
        if ($caller->pObj->extConfPremium['enableBoostKeywords']) {
            if (isset($newsRecord['keywords']) && !empty($newsRecord['keywords'])) {
                $additionalFields['boostkeywords'] = $newsRecord['keywords'];
            }
        }
    }
}
