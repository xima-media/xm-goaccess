<?php

return [
    'ctrl' => [
        'title' => 'Tweet',
        'label' => 'text',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'username',
        'iconfile' => 'EXT:xima_twitter_client/Resources/Public/Icons/tweet.svg',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'account, id, date, author_id, text, attachments, username, name, profile_image',
        ],
    ],
    'columns' => [
        'account' => [
            'label' => 'Account',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_ximatwitterclient_domain_model_account',
            ],
        ],
        'id' => [
            'label' => 'Tweet ID',
            'config' => [
                'type' => 'input',
            ],
        ],
        'date' => [
            'label' => 'Date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],
        'author_id' => [
            'label' => 'Autor ID',
            'config' => [
                'type' => 'input',
            ],
        ],
        'username' => [
            'label' => 'Username',
            'config' => [
                'type' => 'input',
            ],
        ],
        'name' => [
            'label' => 'Name',
            'config' => [
                'type' => 'input',
            ],
        ],
        'text' => [
            'label' => 'Text',
            'config' => [
                'type' => 'text',
            ],
        ],
        'attachments' => [
            'label' => 'Attachments',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'attachments',
                [],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'profile_image' => [
            'label' => 'Attachments',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'profile_image',
                [],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];
