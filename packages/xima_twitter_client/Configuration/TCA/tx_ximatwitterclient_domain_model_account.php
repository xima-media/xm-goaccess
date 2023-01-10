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
            'showitem' => 'username, fetch_type, fetch_options, max_results',
            'max_results'
        ]
    ],
    'columns' => [
        'username' => [
            'label' => 'Username',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required'
            ]
        ],
        'fetch_type' => [
            'label' => 'Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'Latest tweets of user',
                        \Xima\XimaTwitterClient\FetchType\LatestTweets::class
                    ],
                ],
            ],
        ],
        'fetch_options' => [
            'label' => 'Options',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['Include replies', 'includeReplies'],
                    ['Include retweets', 'includeRetweets'],
                ]
            ]
        ],
        'max_results' => [
            'label' => 'Max results',
            'config' => [
                'type' => 'input',
                'eval' => 'int,required',
                'default' => 10
            ],
        ]
    ],
];

