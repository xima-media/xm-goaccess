<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote',
        'quote',
        'content-quote',
    ],
    'image',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['quote'] = 'content-quote';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'tx_xmdkfznetsite_author' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote.author',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote.author.description',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
        ],
    ],
    'tx_xmdkfznetsite_function' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote.author.function',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote.author.function.description',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
        ],
    ],
]);

$GLOBALS['TCA']['tt_content']['palettes']['quote'] = [
    'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:quote.palette',
    'showitem' => 'tx_xmdkfznetsite_author,--linebreak--,tx_xmdkfznetsite_function',
];

$GLOBALS['TCA']['tt_content']['types']['quote'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    bodytext,
                    --palette--;;quote,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;infoboxAppearance,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
    'columnsOverrides' => [
        'sectionIndex' => [
            'config' => [
                'default' => 0,
            ],
        ],
        'bodytext' => [
            'config' => [
                'enableRichtext' => false,
            ],
        ],
    ],
];
