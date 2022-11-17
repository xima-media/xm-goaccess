<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TCA']['pages']['palettes']['media']['showitem'] = '';
$GLOBALS['TCA']['pages']['palettes']['seo']['showitem'] = '';
$GLOBALS['TCA']['pages']['palettes']['robots']['showitem'] = '';

$GLOBALS['TCA']['pages']['palettes']['teaser'] = [
    'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.palettes.teaser',
    'showitem' => 'media,--linebreak--,description,--linebreak--,tx_xmdkfznetsite_contacts',
];
$GLOBALS['TCA']['pages']['palettes']['robots2'] = [
    'label' => 'LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.palettes.robots',
    'showitem' => 'no_index;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_index_formlabel, no_follow;LLL:EXT:seo/Resources/Private/Language/locallang_tca.xlf:pages.no_follow_formlabel',
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;;teaser,',
    '',
    'before:author'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;;robots2,',
    '',
    'before:is_siteroot'
);

$GLOBALS['TCA']['pages']['columns']['description']['config']['cols'] = 60;
$GLOBALS['TCA']['pages']['columns']['description']['config']['rows'] = 10;
$GLOBALS['TCA']['pages']['columns']['media']['label'] = 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.media.label';
$GLOBALS['TCA']['pages']['columns']['media']['description'] = 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.media.description';
$GLOBALS['TCA']['pages']['columns']['media']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [
    'teaser' => [
        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
        'allowedAspectRatios' => [
            '5:2' => [
                'title' => '5:2',
                'value' => 5 / 2,
            ],
        ],
    ],
    'default' => [
        'title' => 'Teaser',
        'allowedAspectRatios' => [
            '3:2' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.3_2',
                'value' => 3 / 2,
            ],
        ],
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', [
    'tx_xmdkfznetsite_color' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.description',
        'l10n_mode' => 'exclude',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => \Xima\XmDkfzNetSite\Tca\TcaUtility::getItemsForColorField(),
        ],
    ],
    'tx_xmdkfznetsite_contacts' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.contacts',
        'config' => [
            'type' => 'group',
            'allowed' => 'fe_users,tx_xmdkfznetsite_domain_model_place',
            'size' => 5,
            'fieldControl' => [
                'addRecord' => [
                    'disabled' => false,
                    'options' => [
                        'table' => 'tx_xmdkfznetsite_domain_model_place',
                        'pid' => '91',
                    ],
                ],
            ],
        ],
    ],
    'tt_content_items' => [
        'label' => 'ITEMS',
        'config' => [
            'foreign_field' => 'foreign_uid',
            'foreign_sortby' => 'sorting',
            'foreign_table' => 'tt_content_item',
            'foreign_table_field' => 'foreign_table',
            'type' => 'inline',
            'minitems' => 0,
            'maxitems' => 3,
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
]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_xmdkfznetsite_color',
    '',
    'after:title'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tt_content_items',
    (string)\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT,
    'after:tx_xmdkfznetsite_color'
);

$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:categories',
    1 => 'sc',
    2 => 'folder-contains-categories',
];
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-sc'] = 'folder-contains-categories';
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee',
    1 => 'cm',
    2 => 'folder-contains-committee',
];
$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-cm'] = 'folder-contains-committee';
$GLOBALS['TCA']['pages']['types'][(string)\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT]['columnsOverrides'] = [
    'tt_content_items' => [
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.tt_content_items',
        'config' => [
            'overrideChildTca' => [
                'columns' => [
                    'record_type' => [
                        'config' => [
                            'default' => 'related-page',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
