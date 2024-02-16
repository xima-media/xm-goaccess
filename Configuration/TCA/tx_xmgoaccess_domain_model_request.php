<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:request',
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'iconfile' => 'EXT:xm_goaccess/Resources/Public/Icons/request.svg',
        'rootLevel' => 1,
    ],
    'types' => [
        0 => [
            'showitem' => 'date,page,hits,visitors,mapping',
        ],
    ],
    'columns' => [
        'date' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:request.date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ],
        ],
        'hits' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:request.hits',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim,int',
                'max' => 255,
            ],
        ],
        'visitors' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:request.visitors',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim,int',
                'max' => 255,
            ],
        ],
        'mapping' => [
            'exclude' => false,
            'label' => 'Mapping',
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];
