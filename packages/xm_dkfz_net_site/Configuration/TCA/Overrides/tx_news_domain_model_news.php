<?php

$GLOBALS['TCA']['tx_news_domain_model_news']['columns']['fal_media']['config']['overrideChildTca']['columns'] = [
    'uid_local' => [
        'config' => [
            'appearance' => [
                'elementBrowserAllowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            ],
        ],
    ],
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
                        '2:3' => [
                            'title' => '2:3',
                            'value' => 2 / 3,
                        ],
                        '4:3' => [
                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.4_3',
                            'value' => 4 / 3,
                        ],
                        '3:4' => [
                            'title' => '3:4',
                            'value' => 3 / 4,
                        ],
                        '1:1' => [
                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.1_1',
                            'value' => 1.0,
                        ],
                    ],
                ],
                'preview' => [
                    'title' => 'Preview',
                    'allowedAspectRatios' => [
                        '1:1' => [
                            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.1_1',
                            'value' => 1.0,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
