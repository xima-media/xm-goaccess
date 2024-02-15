<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'title,path',
        'type' => 'record_type',
        'iconfile' => 'EXT:xm_goaccess/Resources/Public/Icons/mapping.svg',
        'rootLevel' => 1,
    ],
    'types' => [
        0 => [
            'showitem' => '--palette--;;pathSettings, record_type, page',
        ],
        1 => [
            'showitem' => '--palette--;;pathSettings, record_type, title',
        ],
        2 => [
            'showitem' => '--palette--;;pathSettings, record_type',
        ],
        3 => [
            'showitem' => '--palette--;;pathSettings, record_type, foreign_table, foreign_uid',
        ],
    ],
    'palettes' => [
        'pathSettings' => [
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.palettes.path',
            'showitem' => 'path,regex',
        ],
    ],
    'columns' => [
        'regex' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.regex',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                    ],
                ],
            ],
        ],
        'record_type' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Page', 0],
                    ['Action', 1],
                    ['Ignore', 2],
                ],
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.title',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'path' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.path',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'page' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.page',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 1,
                'maxitems' => 1,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => true,
                    ],
                ],
                'fieldWizard' => [
                    'recordsOverview' => [
                        'disabled' => true,
                    ],
                ],
                'default' => 0,
            ],
        ],
        'foreign_uid' => [
            'label' => 'Foreign UID',
            'config' => [
                'type' => 'input',
            ],
        ],
        'foreign_table' => [
            'label' => 'Foreign table',
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];
