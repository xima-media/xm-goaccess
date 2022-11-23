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
    ];

    ExtensionManagementUtility::addTCAcolumns('fe_groups', $tempColumns);
    ExtensionManagementUtility::addToAllTCAtypes(
        'fe_groups',
        'dkfz_number',
        '',
        'after:title'
    );
});
