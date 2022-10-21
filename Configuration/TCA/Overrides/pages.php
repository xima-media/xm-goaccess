<?php
defined('TYPO3_MODE') || die();

// add new field "tx_kesearchpremium_customranking"
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    ['tx_kesearchpremium_customranking' =>
        [
            'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_customranking',
            'config' => [
                'type' => 'input',
                'size' => '10',
                'eval' => 'int',
                'default' => '0',
            ]
        ],
    ]
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_kesearchpremium_customranking',
    '',
    'after:tx_kesearch_resultimage'
);
