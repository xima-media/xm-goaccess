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
];

// Add all fields to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);
