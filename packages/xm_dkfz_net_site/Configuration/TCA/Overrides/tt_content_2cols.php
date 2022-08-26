<?php

\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\B13\Container\Tca\Registry::class)->configureContainer(
    (
    new \B13\Container\Tca\ContainerConfiguration(
        'container-2cols',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols',
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.description',
        [
            [
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.left',
                    'colPos' => 100,
                ],
                [
                    'name' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:container.2cols.right',
                    'colPos' => 200,
                ],
            ],
        ]
    )
    )->setIcon('content-container-columns-2')
);

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', [
    'viewColumn' => [
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.orientation',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.orientation.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                0 => [
                    0 => '50:50',
                    1 => 0,
                ],
                1 => [
                    0 => '75:25',
                    1 => 1,
                ],
            ]
        ]
    ]
]);

$GLOBALS['TCA']['tt_content']['types']['container-2cols']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        viewColumn,
    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
        --palette--;;frames,
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