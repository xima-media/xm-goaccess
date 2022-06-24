<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    $ll = 'LLL:EXT:oauth2/Resources/Private/Language/locallang.xlf:';

    $columns = [
        'oauth_identifier' => [
            'exclude' => 1,
            'label' => $ll . 'columnLabel.oauth_identifier',
            'config' => [
                'type' => 'input',
                'readOnly' => 1,
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_users', $columns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_users', 'oauth_identifier');
});
