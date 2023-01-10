<?php

return [
    'ctrl' => [
        'title' => 'Twitter account',
        'label' => 'username',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'username',
        'iconfile' => 'EXT:xima_twitter_client/Resources/Public/Icons/account.svg',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => 'username'
        ]
    ],
    'columns' => [
        'username' => [
            'label' => 'Username',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required'
            ]
        ]
    ]
];

