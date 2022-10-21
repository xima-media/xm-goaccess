<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// add fields for remote indexer configuration
$tmp_columns = array(
    'remotesite' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remotesite',
        'displayCond' => 'FIELD:type:=:remote',
        'config' => array(
            'type' => 'input',
            'size' => '30',
            'default' => 'http://'
        )
    ),
    'remoteuid' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remoteuid',
        'displayCond' => 'FIELD:type:=:remote',
        'config' => array(
            'type' => 'input',
            'size' => '10',
        )
    ),
    'remoteuser' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remoteuser',
        'displayCond' => 'FIELD:type:=:remote',
        'config' => array(
            'type' => 'input',
            'size' => '30',
        )
    ),
    'remotepass' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remotepass',
        'displayCond' => 'FIELD:type:=:remote',
        'config' => array(
            'type' => 'input',
            'size' => '30',
            'eval' => 'password'
        )
    ),
    'remoteamount' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_remoteamount',
        'displayCond' => 'FIELD:type:=:remote',
        'config' => array(
            'type' => 'input',
            'size' => '10',
            'eval' => 'int',
            'default' => '1000',
        )
    ),
    'customranking' => array(
        'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_customranking',
        'config' => array(
            'type' => 'input',
            'size' => '10',
            'eval' => 'int',
            'default' => '0',
        )
    ),
);

ExtensionManagementUtility::addTCAcolumns('tx_kesearch_indexerconfig', $tmp_columns);
ExtensionManagementUtility::addToAllTCAtypes(
    'tx_kesearch_indexerconfig',
    ',remotesite,remoteuid,remoteuser,remotepass,remoteamount,customranking'
);
