<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3_MODE') || die();

call_user_func(function () {
    $tempColumns = [
        'dkfz_id' => [
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
        'dkfz_id',
        '',
        'after:title'
    );
});
