<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Teaser',
        'page_teaser',
        'content-listgroup',
    ],
    'image',
    'after'
);

$GLOBALS['TCA']['tt_content']['types']['page_teaser'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    tt_content_items,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
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
        'tt_content_items' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.items',
            'config' => [
                'overrideChildTca' => [
                    'types' => [
                        'teaser-item' => [
                            'showitem' => 'record_type, link, link_title, --linebreak--, overrides, --palette--;;teaser-override',
                        ],
                    ],
                    'columns' => [
                        'record_type' => [
                            'config' => [
                                'default' => 'teaser-item',
                            ],
                        ],
                        'image' => [
                            'config' => [
                                'overrideChildTca' => [
                                    'columns' => [
                                        'crop' => [
                                            'config' => [
                                                'cropVariants' => [
                                                    'default' => [
                                                        'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.crop_variant.default',
                                                        'allowedAspectRatios' => [
                                                            '3:2' => [
                                                                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.3_2',
                                                                'value' => 3 / 2,
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'link' => [
                            'config' => [
                                'fieldControl' => [
                                    'linkPopup' => [
                                        'options' => [
                                            'blindLinkFields' => 'class,params,target,title',
                                            'blindLinkOptions' => 'file,folder,mail,telephone,url',
                                        ],
                                    ],
                                ],
                                'eval' => 'required',
                            ],
                        ],
                        'tt_content_items' => [
                            'label' => 'Links',
                            'config' => [
                                'overrideChildTca' => [
                                    'columns' => [
                                        'record_type' => [
                                            'config' => [
                                                'default' => 'link',
                                            ],
                                        ],
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
$GLOBALS['TCA']['tt_content']['types']['page_teaser']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\TeaserPreviewRenderer::class;
