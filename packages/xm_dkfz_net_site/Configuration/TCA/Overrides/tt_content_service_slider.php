<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'ServiceSlider',
        'serviceSlider',
        'content-carousel',
    ],
    'uploads',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['carousel'] = 'content-carousel';

$GLOBALS['TCA']['tt_content']['types']['serviceSlider'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    tt_content_items,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;appearanceLinks,
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
        'tt_content_items' => [
            'label' => 'Service-Slider',
            'config' => [
                'overrideChildTca' => [
                    'types' => [
                        'teaser-item' => [
                            'showitem' => 'record_type, link, --linebreak--, overrides, --palette--;;slider-override',
                        ],
                    ],
                    'columns' => [
                        'record_type' => [
                            'config' => [
                                'default' => 'teaser-item',
                                'items' => [
                                    [
                                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser',
                                        'teaser-item',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];

// Register custom Preview
$GLOBALS['TCA']['tt_content']['types']['linklist']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\InfoboxPreviewRenderer::class;

