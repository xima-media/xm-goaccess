<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact',
        'label' => 'position',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'position,phone_number,room',
        'hideTable' => true,
        'type' => 'record_type',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-user-contact.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'position,phone_number,room',
    ],
    'types' => [
        0 => [
            'showitem' => 'record_type,fe_user,fe_group,position,room,phone_number,primary_number',
        ],
    ],
    'columns' => [
        'record_type' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.record_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.record_type.0',
                        0,
                    ],
                    [
                        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.record_type.1',
                        1,
                    ],
                ],
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'position' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.position',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'room' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'phone_number' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user_contact.phone_number',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'fe_user' => [
            'label' => 'fe_user',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
            ],
        ],
        'fe_group' => [
            'label' => 'fe_groups',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_groups',
            ],
        ],
    ],
];
