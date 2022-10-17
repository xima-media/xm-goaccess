<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item',
        'label' => 'title',
        'label_alt' => 'link',
        'delete' => 'deleted',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,text',
        'hideTable' => true,
        'type' => 'record_type',
        'typeicon_column' => 'record_type',
        'typeicon_classes' => [
            'default' => 'content-extension',
            'teaser-item' => 'content-card',
            'link' => 'content-thumbtack',
            'accordion-item' => 'content-accordion',
        ],
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, text',
    ],
    'types' => [
        0 => [
            'showitem' => 'record_type',
        ],
        'teaser-item' => [
            'showitem' => 'record_type, link, link_title, overrides, --palette--;;teaser-override',
        ],
        'link' => [
            'showitem' => 'record_type, title;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.link.title, link',
        ],
        'accordion-item' => [
            'showitem' => 'record_type, title',
        ],
    ],
    'palettes' => [
        'teaser-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.palette.override',
            'showitem' => 'title,color,--linebreak--,text,--linebreak--,image,--linebreak--,tt_content_items',
        ],
        'slider-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.palette.override',
            'showitem' => 'title,--linebreak--,text,--linebreak--',
        ],
    ],
    'columns' => [
        'record_type' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'readOnly' => true,
                'items' => [
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-item',
                        'teaser-item',
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.link',
                        'link',
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.accordion-title',
                        'accordion-item',
                    ],
                ],
            ],
        ],
        'sys_language_uid' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0,
                    ],
                ],
                'foreign_table' => 'tt_content_item',
                'foreign_table_where' => 'AND tt_content_item.pid=###CURRENT_PID### AND tt_content_item.sys_language_uid IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'foreign_uid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'foreign_table' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.title',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
            ],
        ],
        'text' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.text',
            'config' => [
                'placeholder' => '',
                'type' => 'text',
                'enableRichtext' => false,
            ],
        ],
        'link_title' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.link-title',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
            ],
        ],
        'link' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
            ],
        ],
        'image' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.image',
            'exclude' => false,
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'image',
                [
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.color',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', ''],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.primary',
                        'primary',
                    ],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.green', 'green'],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.magenta',
                        'magenta',
                    ],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.cyan', 'cyan'],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.gray-500',
                        'gray-500',
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.blue-900',
                        'blue-900',
                    ],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.orange', 'orange'],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.color.green-light',
                        'green-light',
                    ],
                ],
                'default' => '',
            ],
        ],
        'overrides' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.overrides',
            'config' => [
                'type' => 'check',
                'renderType' => 'overrideToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'tt_content_items' => [
            'label' => 'ITEMS',
            'config' => [
                'foreign_field' => 'foreign_uid',
                'foreign_sortby' => 'sorting',
                'foreign_table' => 'tt_content_item',
                'foreign_table_field' => 'foreign_table',
                'type' => 'inline',
                'minitems' => 0,
                'appearance' => [
                    'collapseAll' => true,
                    'expandSingle' => true,
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                        'info' => false,
                    ],
                ],
            ],
        ],
    ],
];
