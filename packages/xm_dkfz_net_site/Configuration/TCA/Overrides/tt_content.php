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
                true,
                ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.0', '']
            ),
            'default' => '',
        ],
    ],
];

// Add all fields to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

// Add color to appearance tab of selected CTypes
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'color',
    'textmedia',
    'before:sectionIndex'
);
