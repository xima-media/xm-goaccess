<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'pages',
    'doktype',
    [
        'LLL:EXT:xm_manual/Resources/Private/Language/locallang.xlf:manual_page_type',
        701,
        'EXT:xm_manual/Resources/Public/Icons/apps-pagetree-manual.svg',
    ],
    '1',
    'after'
);

\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
    $GLOBALS['TCA']['pages'],
    [
        'ctrl' => [
            'typeicon_classes' => [
                701 => 'apps-pagetree-manual',
                '701-contentFromPid' => 'apps-pagetree-manual-contentFromPid',
                '701-root' => 'apps-pagetree-manual-root',
                '701-hideinmenu' => 'apps-pagetree-manual-hideinmenu',
            ],
        ],
        'types' => [
            701 => [
                'showitem' => '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;;standard,
                        --palette--;;title,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.resources,
                        --palette--;;media,
                        --palette--;;config,is_siteroot,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                        --palette--;;visibility,',
            ],
        ],
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    'xm_manual',
    'Configuration/TSconfig/Page.tsconfig',
    'Editor Manual',
);
