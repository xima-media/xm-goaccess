<?php
namespace Tpwd\KeSearchPremium\Hooks;

use Exception;
use Tpwd\KeSearch\Indexer\Types\Page as PageIndexer;
use Tpwd\KeSearch\Lib\Db;
use Tpwd\KeSearch\Lib\SearchHelper;

class CustomRanking
{
    /**
     * Modifies the search query and casts the customranking to a number to ensure correct sorting
     * (additionalFields in ke_search must be string, so we cannot use an integer field)
     *
     * @param array $queryParts
     * @param Db $caller
     * @param $searchwordQuoted
     * @return array
     * @throws Exception
     */
    public function getQueryParts(array $queryParts, Db $caller, $searchwordQuoted)
    {
        if ($caller->pObj->extConfPremium['enableCustomRanking']) {
            $queryParts['ORDERBY'] = str_replace('customranking desc', 'CAST(customranking AS SIGNED) desc', $queryParts['ORDERBY']);
            $queryParts['ORDERBY'] = str_replace('customranking asc', 'CAST(customranking AS SIGNED) asc', $queryParts['ORDERBY']);
        }
        return $queryParts;
    }

    /**
     * @param array $additionalFields
     */
    public function registerAdditionalFields(&$additionalFields)
    {
        $additionalFields[] = 'customranking';
    }

    /**
     * Fetch value tx_kesearchpremium_customranking
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
        if ($caller->pObj->extConfPremium['enableCustomRanking']) {
            // Save a serialized array of the customrankings of all languages because unfortunately we cannot
            // differentiate at this point for which language we want to set the additionalFields.
            $customrankings = [];
            foreach ($cachedPageRecords as $language => $pageRecords) {
                if (isset($pageRecords[$uid]['tx_kesearchpremium_customranking']) && $pageRecords[$uid]['tx_kesearchpremium_customranking']) {
                    $customrankings[$language] = $pageRecords[$uid]['tx_kesearchpremium_customranking'];
                }
            }
            if ($customrankings) {
                $additionalFields['customranking'] = serialize($customrankings);
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
        $extConfPremium = SearchHelper::getExtConfPremium();
        if ($extConfPremium['enableCustomRanking']) {
            // Field "customranking" from pages contains the serialized values of multiple languages
            if (
                $fieldValues['type'] == 'page' &&
                isset($fieldValues['customranking'])
                && !empty($fieldValues['customranking'])
            ) {
                $customrankings = unserialize($fieldValues['customranking']);
                $fieldValues['customranking'] = intval($customrankings[$fieldValues['language']]);
            } else {
                // Value for customranking must be initialized before it goes to the database, but
                // the value may have been modified earlier, e. g. in the hook "modifyExtNewsIndexEntry",
                // so we initialize it only if it is not already set.
                if (!$fieldValues['customranking']) {
                    $fieldValues['customranking'] = 0;
                }
            }

            // Add "customranking" field from indexer configuration
            if (isset($indexerConfig['customranking']) && intval($indexerConfig['customranking'])) {
                $fieldValues['customranking'] += intval($indexerConfig['customranking']);
            }
        }
        return $fieldValues;
    }
}
