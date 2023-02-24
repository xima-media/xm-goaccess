<?php

$tempFields = [
    'tt_content_items' => [
        'label' => 'ITEMS',
        'config' => [
            'foreign_field' => 'foreign_uid',
            'foreign_sortby' => 'sorting',
            'foreign_table' => 'tt_content_item',
            'foreign_table_field' => 'foreign_table',
            'type' => 'inline',
            'minitems' => 0,
            'appearance' => [
                'collapseAll' => true,
                'expandSingle' => true,
                'useSortable' => true,
                'enabledControls' => [
                    'dragdrop' => true,
                    'info' => false,
                ],
            ],
        ],
    ],
    'color' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => \Xima\XmDkfzNetSite\Tca\TcaUtility::getItemsForColorField(
                [
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.0', ''],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.transparent', 'transparent'],
                ]
            ),
            'default' => '',
        ],
    ],
    'employees' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.employee',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_users',
            'foreign_table_where' => 'ORDER BY fe_users.crdate',
            'size' => 5,
            'minitems' => 1,
        ],
    ],
    'news' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.news',
        'config' => [
            'type' => 'group',
            'allowed' => 'tx_news_domain_model_news',
            'size' => 5,
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
        ],
    ],
];

// Add all fields to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

// Add color to frame tab of all CTypes
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'frames',
    'color',
    'before:layout'
);

// Register Place plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'XmDkfzNetSite',
    'Placelist',
    'Place',
    'tx_bwguild_offerlist'
);
