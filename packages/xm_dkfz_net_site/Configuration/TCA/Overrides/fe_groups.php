<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(function () {
    $tempColumns = [
        'dkfz_number' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.dkfz_id',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
            ],
        ],
        'secretaries' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:group.secretaries',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
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
        'managers' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:group.managers',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
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
        'assistants' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:group.assistants',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
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
        'coordinators' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:group.coordinators',
            'config' => [
                'type' => 'group',
                'allowed' => 'fe_users',
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
        'dkfz_hash' => [
            'label' => 'Hash',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('fe_groups', $tempColumns);
    ExtensionManagementUtility::addToAllTCAtypes(
        'fe_groups',
        'dkfz_number,secretaries,managers,assistants,coordinators',
        '',
        'after:title'
    );
});
