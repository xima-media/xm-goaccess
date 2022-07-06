<?php

$GLOBALS['TCA']['tt_content']['types']['textmedia']['columnsOverrides']['assets']['config']['overrideChildTca']['columns']['crop']['config'] = [
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
            'selectedRatio' => '3:2',
            'cropArea' => [
                'x' => 0.0,
                'y' => 0.0,
                'width' => 1.0,
                'height' => 1.0,
            ],
        ],
    ],
];
