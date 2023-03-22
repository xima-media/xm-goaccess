<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping',
        'label' => 'title',
        'delete' => 'deleted',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,path',
        'type' => 'record_type',
        'iconfile' => 'EXT:xm_goaccess/Resources/Public/Icons/mapping.svg',
    ],
    'types' => [
        0 => [
            'showitem' => 'hidden, path, record_type, page, title',
        ],
    ],
    'columns' => [
        'record_type' => [
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['Page', 0],
                    ['Action', 1],
                ],
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.title',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'path' => [
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.path',
            'config' => [
                'placeholder' => '',
                'type' => 'input',
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'page' => [
            'label' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang.xlf:mapping.page',
            'config' => [
                'type' => 'group',
                'allowed' => 'pages',
                'size' => 1,
                'minitems' => 0,
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
            ],
        ],
    ],
];
