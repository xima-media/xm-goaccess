<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

$GLOBALS['TCA']['fe_users']['ctrl']['label_alt'] = 'title, last_name, first_name';
$GLOBALS['TCA']['fe_users']['ctrl']['label_alt_force'] = true;
$GLOBALS['TCA']['fe_users']['columns']['slug']['config']['generatorOptions']['fields'] = [
    'first_name',
    'last_name',
    'name',
];
$GLOBALS['TCA']['fe_users']['columns']['bookmarks']['config']['allowed'] = 'pages,fe_users,sys_file,tx_news_domain_model_news';

// crop variant
$GLOBALS['TCA']['fe_users']['columns']['logo']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [
    'square' => [
        'title' => 'Square',
        'allowedAspectRatios' => [
            '1:1' => [
                'title' => 'Square',
                'value' => 1,
            ],
        ],
    ],
];

call_user_func(function () {
    $tempColumns = [
        'location' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.location',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'member_since' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.member_since',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date,int',
                'format' => 'date',
            ],
        ],
        'birthday' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.birthday',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date,int',
                'format' => 'date',
            ],
        ],
        'dkfz_id' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.dkfz_id',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
            ],
        ],
        'ad_account_name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.ad_account_name',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
            ],
        ],
        'contacts' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.contacts',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_xmdkfznetsite_domain_model_contact',
                'foreign_field' => 'foreign_uid',
                'foreign_table_field' => 'foreign_table',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                        'info' => false,
                    ],
                ],
            ],
        ],
        'dkfz_hash' => [
            'exlude' => false,
            'label' => 'Hash of CPerson node',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'gender' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.gender',
            'config' => [
                'type' => 'select',
                'readOnly' => true,
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.gender.0', 0],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.gender.1', 1],
                    ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.gender.2', 2],
                ],
                'default' => 0,
            ],
        ],
        'responsibilities' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.responsibilities',
            'config' => [
                'type' => 'text',
            ],
        ],
        'representative' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.representative',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
                'size' => 1,
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
        'committee' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.committee',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'default' => 0,
                'foreign_table' => 'tx_xmdkfznetsite_domain_model_committee',
                'foreign_table_where' => ' AND {#tx_xmdkfznetsite_domain_model_committee}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'committee_responsibilities' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.committee_responsibilities',
            'config' => [
                'type' => 'text',
            ],
        ],
        'committee_representative' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.committee_representative',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
                'size' => 1,
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
        'about' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.about',
            'config' => [
                'type' => 'text',
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumns);
    ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'location,member_since,birthday,gender,dkfz_id,ad_account_name,contacts,responsibilities,representative,committee,committee_responsibilities,committee_representative,about',
        '',
        'after:email'
    );
});
