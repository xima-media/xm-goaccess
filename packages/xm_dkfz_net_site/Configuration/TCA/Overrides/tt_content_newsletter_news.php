<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.newsletter-news.title',
        'newsletter-news',
        'content-listgroup',
    ],
    'uploads',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['newsletter-news'] = 'content-listgroup';

$GLOBALS['TCA']['tt_content']['types']['newsletter-news'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    news,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,',
    'columnsOverrides' => [

    ],
];

// Register custom Preview
$GLOBALS['TCA']['tt_content']['types']['newsletter-news']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\InfoboxPreviewRenderer::class;
