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
            'showitem' => 'id, author_id, text, attachments'
        ]
    ],
    'columns' => [
        'id' => [
            'label' => 'Tweet ID',
            'config' => [
                'type' => 'input'
            ]
        ],
        'author_id' => [
            'label' => 'Autor ID',
            'config' => [
                'type' => 'input'
            ]
        ],
        'text' => [
            'label' => 'Text',
            'config' => [
                'type' => 'text'
            ]
        ],
        'attachments' => [
            'label' => 'Attachments',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'attachments', [], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            )
        ]
    ]
];

