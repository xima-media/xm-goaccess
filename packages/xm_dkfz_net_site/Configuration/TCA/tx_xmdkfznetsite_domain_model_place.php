<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'name,function,room',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-place.svg',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
    ],
    'types' => [
        0 => [
            'showitem' => 'dkfz_id,name,function,room,mail,fe_group,contacts',
        ],
    ],
    'columns' => [
        'name' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'function' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.function',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'mail' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.mail',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'room' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.room',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'contacts' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.contacts',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_xmdkfznetsite_domain_model_contact',
                'foreign_field' => 'foreign_uid',
                'foreign_table_field' => 'foreign_table',
                'foreign_sortby' => 'sorting',
                'appearance' => [
                    'useSortable' => true,
                    'enabledControls' => [
                        'dragdrop' => true,
                        'info' => false,
                    ],
                ],
            ],
        ],
        'fe_group' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_groups',
            ],
        ],
        'dkfz_id' => [
            'exclude' => false,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:user.dkfz_id',
            'config' => [
                'type' => 'input',
                'readOnly' => true,
            ],
        ],
        'dkfz_hash' => [
            'exlude' => false,
            'label' => 'Hash of CPerson node',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
