<?php
if (!defined("TYPO3_MODE")) {
    die ("Access denied.");
}

// modify no results text hook
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableDoYouMean')
) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['noResultsHandler'][]
        = Tpwd\KeSearchPremium\Hooks\NoResultsHandler::class;
}

// modify search words hook
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableSynonyms')
) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifySearchWords'][]
        = Tpwd\KeSearchPremium\Hooks\ModifySearchWords::class;
}

// register eID script for autocomplete
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableAutocomplete')
) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['keSearchPremiumAutoComplete']
        = \Tpwd\KeSearchPremium\Middleware\Autocomplete::class . '::findWordsWhichBeginsWith';
}

// register eID script for api
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_kesearchpremium_api']
    = Tpwd\KeSearchPremium\Middleware\Api::class . '::soapServer';


// distance search hooks
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableDistanceSearch')
) {
    // register additional fields (lat, lon) for index table
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['registerAdditionalFields'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for geocoding tt_address entries while indexing
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyAddressIndexEntry'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for displaying the distance search filter in the frontend
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['customFilterRenderer'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for removing the "distance" and "radius" filter from the normal tag based search
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyTagsAgainst'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for manipulating the search query in order to add the distance search related parts
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['getQueryParts'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for displaying the distance in the search result list
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['additionalResultMarker'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for removing the order links once the distance filter is active (ordering is then always "distance ASC")
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyResultList'][]
        = Tpwd\KeSearchPremium\Distancesearch::class;

    // hook for displaying the map
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyResultList'][]
        = Tpwd\KeSearchPremium\Googlemaps::class;
}

// boost keywords
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('ke_search_premium', 'enableBoostKeywords')
) {
    // register hook to modify the query
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['getQueryParts'][]
        = Tpwd\KeSearchPremium\Hooks\BoostKeywords::class;

    // register additional field "boostkeywords"
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['registerAdditionalFields'][]
        = Tpwd\KeSearchPremium\Hooks\BoostKeywords::class;

    // register hook for page indexer
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyPagesIndexEntry'][]
        = Tpwd\KeSearchPremium\Hooks\BoostKeywords::class;

    // register hook to process values directly before storing them into the index
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyFieldValuesBeforeStoring'][]
        = Tpwd\KeSearchPremium\Hooks\BoostKeywords::class;

    // register hook for news indexer
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyExtNewsIndexEntry'][]
        = Tpwd\KeSearchPremium\Hooks\BoostKeywords::class;
}

// custom ranking
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('ke_search_premium', 'enableCustomRanking')
) {
    // register additional field "customranking"
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['registerAdditionalFields'][]
        = Tpwd\KeSearchPremium\Hooks\CustomRanking::class;

    // register hook to modify the query
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['getQueryParts'][]
        = Tpwd\KeSearchPremium\Hooks\CustomRanking::class;

    // register hook for page indexer
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyPagesIndexEntry'][]
        = Tpwd\KeSearchPremium\Hooks\CustomRanking::class;

    // register hook to process values directly before storing them into the index
    $GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['modifyFieldValuesBeforeStoring'][]
        = Tpwd\KeSearchPremium\Hooks\CustomRanking::class;
}

// register new indexer type "external website"
$GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['registerIndexerConfiguration'][]
    = Tpwd\KeSearchPremium\Fetchremote::class;

$GLOBALS['TYPO3_CONF_VARS']['EXTENSION']['ke_search']['customIndexer'][]
    = Tpwd\KeSearchPremium\Fetchremote::class;

// register geocoding cache
if (empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_kesearchpremium_geocode'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_kesearchpremium_geocode'] = [];
}

// include static template for autocomplete
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableAutocomplete')
) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        \TYPO3\CMS\Core\Utility\GeneralUtility::getURL(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search_premium') . 'Configuration/TypoScript/Autocomplete/constants.typoscript'
        )
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        \TYPO3\CMS\Core\Utility\GeneralUtility::getURL(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search_premium') . 'Configuration/TypoScript/Autocomplete/setup.typoscript'
        )
    );
}

// include static template for distance search
if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableDistanceSearch')
) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        \TYPO3\CMS\Core\Utility\GeneralUtility::getURL(
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('ke_search_premium') . 'Configuration/TypoScript/Distancesearch/setup.typoscript'
        )
    );
}

// logging
$extConf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get('ke_search');
$loglevel = !empty($extConf['loglevel']) ? $extConf['loglevel'] : 'ERROR';
$loglevel = strtolower($loglevel);
$GLOBALS['TYPO3_CONF_VARS']['LOG']['Tpwd']['KeSearchPremium']['writerConfiguration'] = [
    $loglevel => [
        'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => [
            'logFileInfix' => 'kesearch'
        ]
    ]
];

