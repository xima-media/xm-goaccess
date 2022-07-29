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
    'interface' => [
        'showRecordFieldList' => 'position,phone_number,room',
    ],
    'types' => [
        0 => [
            'showitem' => 'name,function,room,contacts,fe_group',
        ],
    ],
    'columns' => [
        'name' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:place.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
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
