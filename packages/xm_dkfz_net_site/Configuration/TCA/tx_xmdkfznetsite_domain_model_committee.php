<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'name',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-committee.svg',
        'languageField' => 'sys_language',
        'transOrigPointerField' => 'l10n_parent',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'name',
    ],
    'types' => [
        0 => [
            'showitem' => '--palette--;;general',
        ],
    ],
    'palettes' => [
        'general' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee.palettes.general',
            'showitem' => 'sys_language, --linebreak--, name',
        ],
    ],
    'columns' => [
        'sys_language' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'name' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
    ],
];
