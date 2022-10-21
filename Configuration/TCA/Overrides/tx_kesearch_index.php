<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// add fields for geocoding and remote indexer transfer
$tmp_columns = array(
    'lat' => array(
        'config' => array(
            'type' => 'passthrough'
        )
    ),
    'long' => array(
        'config' => array(
            'type' => 'passthrough'
        )
    ),
    'lastremotetransfer' => array(
        'config' => array(
            'type' => 'passthrough',
            'eval' => 'date'
        )
    ),
    'externalurl' => array(
        'config' => array(
            'type' => 'passthrough',
        )
    ),
    'boostkeywords' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearch_index.boostkeywords',
        'config' => array(
            'type' => 'passthrough',
        )
    ),
    'customranking' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearch_index.customranking',
        'config' => array(
            'type' => 'passthrough',
        )
    ),
);

ExtensionManagementUtility::addTCAcolumns('tx_kesearch_index', $tmp_columns);
ExtensionManagementUtility::addToAllTCAtypes('tx_kesearch_index', ',lat,long,lastremotetransfer,externalurl,boostkeywords,customranking');

