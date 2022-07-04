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
                    'allowed' => ['CType' => '*'],
                ],
            ],
        ]
    )
    )->setIcon('content-accordion')
    ->setBackendTemplate('EXT:xm_dkfz_net_site/Resources/Private/Extensions/container/Templates/Accordion-Preview.html')
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'tx_xmdkfznetsite_accordion_title' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.title',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.accordion.title.description',
        'config' => [
            'type' => 'input',
            'eval' => 'trim,required',
        ],
    ],
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
    'showitem' => 'tx_xmdkfznetsite_accordion_title,--linebreak--,tx_xmdkfznetsite_accordion_group',
];

$GLOBALS['TCA']['tt_content']['types']['container-accordion']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;accordion,
        --palette--;;headers,
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
