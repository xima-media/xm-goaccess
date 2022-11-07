<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact',
        'label' => 'number',
        'label_alt' => 'record_type',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'function,phone_number,room',
        'hideTable' => true,
        'type' => 'record_type',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-contact.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'function,phone_number,room',
    ],
    'types' => [
        0 => [
            'showitem' => 'record_type,number,fe_group,room,function,primary_number',
        ],
        2 => [
            'showitem' => 'record_type,number,fe_group,room,function,primary_number',
            'columnsOverrides' => [
                'number' => [
                    'label' => 'E-Mail',
                ],
            ],
        ],
    ],
    'columns' => [
        'record_type' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.record_type.0',
                        0,
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.record_type.1',
                        1,
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.record_type.2',
                        2,
                    ],
                ],
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'function' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.function',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'room' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'number' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.number',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'fe_group' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_groups',
                'items' => [
                    ['', 0],
                ],
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'primary_number' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:contact.primary_number',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
];
