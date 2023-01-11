<?php

return [
    'ctrl' => [
        'title' => 'Tweet',
        'label' => 'username',
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
    ]
];

