<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item',
        'label' => 'title',
        'label_alt' => 'link,fe_user',
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
            'user-contact' => 'content-user',
            'related-page' => 'content-menu-sitemap-pages',
            'teaser-link' => 'content-thumbtack',
        ],
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
    ],
    'types' => [
        0 => [
            'showitem' => 'record_type',
        ],
        'teaser-item' => [
            'showitem' => 'sys_language_uid, record_type, link, link_title, overrides, --palette--;;teaser-override',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-item',
                                'teaser-item',
                            ],
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-link',
                                'teaser-link',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'teaser-link' => [
            'showitem' => 'sys_language_uid, record_type, link',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-item',
                                'teaser-item',
                            ],
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-link',
                                'teaser-link',
                            ],
                        ],
                    ],
                ],
                'link' => [
                    'label' => 'External link',
                    'config' => [
                        'type' => 'select',
                        'renderType' => 'selectSingle',
                        'foreign_table' => 'tx_xmdkfznetsite_domain_model_external_link',
                        'foreign_table_where' => 'AND tx_xmdkfznetsite_domain_model_external_link.sys_language_uid = ###REC_FIELD_sys_language_uid###',
                    ],
                ],
            ],
        ],
        'link' => [
            'showitem' => 'sys_language_uid, record_type, title;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.link.title, link',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.link',
                                'link',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'accordion-item' => [
            'showitem' => 'sys_language_uid, record_type, title',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.accordion-item',
                                'accordion-item',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'user-contact' => [
            'showitem' => 'sys_language_uid, record_type,fe_user,overrides;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.user-contact.override,--palette--,contacts,overrides2;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.user-contact.override2,--palette--,text;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.responsibilities ,overrides3;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.user-contact.override3,--palette--,fe_users',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.user-contact',
                                'user-contact',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'related-page' => [
            'showitem' => 'sys_language_uid, record_type, page, overrides, --palette--;;related-page-override',
            'columnsOverrides' => [
                'record_type' => [
                    'config' => [
                        'items' => [
                            [
                                'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.related-page',
                                'related-page',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'palettes' => [
        'teaser-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.palette.override',
            'showitem' => 'title,color,overrides2;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type.teaser-item.override2;,--linebreak--,text,--linebreak--,image,--linebreak--,tt_content_items',
        ],
        'slider-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.palette.override',
            'showitem' => 'title,--linebreak--,text,--linebreak--',
        ],
        'fe_user-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_usertable.palette.override',
            'showitem' => 'contacts, --linebreak--, text;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.responsibilities',
        ],
        'related-page-override' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.palette.related-page-override',
            'showitem' => 'title;LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:pages.palette.related-page-override.title, --linebreak--, link_title',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'label' => 'Language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'record_type' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_item.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
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
                'eval' => 'trim',
                'max' => 255,
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
                'eval' => 'trim',
                'max' => 255,
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
        'overrides2' => [
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
        'overrides3' => [
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
        'fe_user' => [
            'label' => 'User',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => true,
                    ],
                ],
                'fieldWizard' => [
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'fe_users' => [
            'label' => 'Users',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
                'size' => 2,
                'minitems' => 0,
                'maxitems' => 2,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => true,
                    ],
                ],
                'fieldWizard' => [
                    'recordsOverview' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'contacts' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.contacts',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_xmdkfznetsite_domain_model_contact',
                'foreign_table_where' => '{#tx_xmdkfznetsite_domain_model_contact}.{#foreign_uid} = ###REC_FIELD_fe_user### AND {#tx_xmdkfznetsite_domain_model_contact}.{#foreign_table} = "fe_users"',
                'size' => 3,
                'default' => 0,
            ],
        ],
        'page' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:page',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => true,
                    ],
                ],
                'fieldWizard' => [
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
    ],
];
