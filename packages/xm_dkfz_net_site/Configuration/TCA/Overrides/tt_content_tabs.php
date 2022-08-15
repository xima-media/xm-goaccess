<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-tabs',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.description',
        [
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.tab1',
                    'colPos' => 101,
                    'allowed' => ['CType' => 'uploads, textmedia, linklist'],
                ],
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.tab2',
                    'colPos' => 102,
                    'allowed' => ['CType' => 'uploads, textmedia, linklist'],
                ],
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.tab3',
                    'colPos' => 103,
                    'allowed' => ['CType' => 'uploads, textmedia, linklist'],
                ],
            ],
        ]
    )
    )->setIcon('content-tab')
    ->setSaveAndCloseInNewContentElementWizard(false)
    ->setBackendTemplate('EXT:xm_dkfz_net_site/Resources/Private/Extensions/container/Templates/Tabs-Preview.html')
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'tx_xmdkfznetsite_tabs_tab1' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.title1',
        'config' => [
            'type' => 'input',
            'eval' => 'trim,required',
        ],
    ],
    'tx_xmdkfznetsite_tabs_tab2' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.title2',
        'config' => [
            'type' => 'input',
            'eval' => 'trim,required',
        ],
    ],
    'tx_xmdkfznetsite_tabs_tab3' => [
        'exclude' => 0,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.title3',
        'config' => [
            'type' => 'input',
            'eval' => 'trim',
        ],
    ],
]);

$GLOBALS['TCA']['tt_content']['palettes']['tabs'] = [
    'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.tabs.palette',
    'showitem' => 'tx_xmdkfznetsite_tabs_tab1,tx_xmdkfznetsite_tabs_tab2,tx_xmdkfznetsite_tabs_tab3',
];

$GLOBALS['TCA']['tt_content']['types']['container-tabs']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        --palette--;;headers,
        --palette--;;tabs,
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
