<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Infobox',
        'infobox',
        'content-info',
    ],
    'image',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['infobox'] = 'content-info';

$GLOBALS['TCA']['tt_content']['types']['infobox'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    bodytext,
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
        'sectionIndex' => [
            'config' => [
                'default' => 0,
            ],
        ],
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'minimal'
            ],
        ],
        'tt_content_items' => [
            'label' => 'Links',
            'config' => [
                'overrideChildTca' => [
                    'types' => [
                        'link' => [
                            'showitem' => 'record_type, title, link',
                        ],
                    ],
                    'columns' => [
                        'record_type' => [
                            'config' => [
                                'default' => 'link',
                            ],
                        ],
                        'title' => [
                            'label' => 'Linktext',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

// Register custom Preview
$GLOBALS['TCA']['tt_content']['types']['infobox']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\InfoboxPreviewRenderer::class;
