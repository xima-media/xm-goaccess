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
        'transOrigPointerField' => 'l18n_parent',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
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
            'showitem' => 'sys_language_uid, --linebreak--, name',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:committee.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0,
                    ],
                ],
                'foreign_table' => 'tx_xmdkfznetsite_domain_model_committee',
                'foreign_table_where' =>
                    'AND {#tx_xmdkfznetsite_domain_model_committee}.{#pid}=###CURRENT_PID###'
                    . ' AND {#tx_xmdkfznetsite_domain_model_committee}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
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
