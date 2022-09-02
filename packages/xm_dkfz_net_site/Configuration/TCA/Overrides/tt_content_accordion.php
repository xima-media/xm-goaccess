<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-accordion',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.description',
        [
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 100,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 200,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 300,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 400,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 500,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 600,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 700,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 800,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 900,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.content',
                    'colPos' => 1000,
                    'disallowed' => ['CType' => 'container-accordion'],
                ],
            ],
        ]
    )
    )->setIcon('content-accordion')
        ->setBackendTemplate('EXT:xm_dkfz_net_site/Resources/Private/Extensions/container/Templates/Accordion-Preview.html')
);

$GLOBALS['TCA']['tt_content']['types']['container-accordion']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\AccordionContainerPreviewRenderer::class;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'tx_xmdkfznetsite_accordion_group' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.group',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.group.description',
        'config' => [
            'type' => 'input',
        ],
    ],
]);

$GLOBALS['TCA']['tt_content']['palettes']['accordion'] = [
    'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.palette',
    'showitem' => 'tx_xmdkfznetsite_accordion_group,--linebreak--,tt_content_items',
];

$GLOBALS['TCA']['tt_content']['types']['container-accordion']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;;accordion,
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
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
';

$GLOBALS['TCA']['tt_content']['types']['container-accordion']['columnsOverrides']['tt_content_items'] = [
    'label' => 'Elemente',
    'config' => [
        'overrideChildTca' => [
            'columns' => [
                'record_type' => [
                    'config' => [
                        'default' => 'accordion-item',
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.accordion-title',
                                'accordion-item',
                            ],
                        ],
                    ],
                ],
                'title' => [
                    'label' => 'Accordion-Title',
                ],
            ],
        ],
    ],
];
