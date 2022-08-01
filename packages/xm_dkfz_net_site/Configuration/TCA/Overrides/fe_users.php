<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3_MODE') || die();

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
                'eval' => 'datetime',
                'format' => 'date',
            ],
        ],
        'birthday' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.birthday',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
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
    ];

    ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumns);
    ExtensionManagementUtility::addToAllTCAtypes(
        'fe_users',
        'location,member_since,birthday,gender,dkfz_id,ad_account_name,contacts',
        '',
        'after:email'
    );
});
