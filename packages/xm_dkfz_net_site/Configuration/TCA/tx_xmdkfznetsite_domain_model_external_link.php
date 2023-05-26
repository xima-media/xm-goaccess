<?php

return [
    'ctrl' => [
        'title' => 'Link',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'title,link,description',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Icons/pin.svg',
    ],
    'types' => [
        0 => [
            'showitem' => 'title,url,description',
        ],
    ],
    'columns' => [
        'title' => [
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'url' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.name',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'eval' => 'required',
                'max' => 255,
            ],
        ],
        'description' => [
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
            ],
        ],
    ],
];
