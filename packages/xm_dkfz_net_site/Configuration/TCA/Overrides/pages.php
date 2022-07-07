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
$GLOBALS['TCA']['pages']['columns']['media']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [
    'default' => [
        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
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
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => \Xima\XmDkfzNetSite\Tca\TcaUtility::getItemsForColorField(),
            'default' => '',
        ],
    ],
    'tx_xmdkfznetsite_contacts' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.contacts',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_users',
            'size' => 5,
            'minitems' => 0,
        ],
    ],
]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    'tx_xmdkfznetsite_color',
    '',
    'after:title'
);
