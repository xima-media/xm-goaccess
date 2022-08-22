<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor',
        'label' => 'headline',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime'
        ],
        'searchFields' => 'headline, text, starttime, endtime',
        'iconfile' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/icon-info.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'headline, text, starttime, endtime',
    ],
    'types' => [
        0 => [
            'showitem' => 'headline, text, starttime, endtime',
        ],
    ],
    'columns' => [
        'headline' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor.headline',
            'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor.headline.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'text' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor.text',
            'config' => [
                'type' => 'text',
                'eval' => 'required',
                'max' => 140,
            ]
        ],
        'starttime' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'required' => true,
                'eval' => 'datetime,int',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:disruptor.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'required' => true,
                'eval'    => 'datetime,int',
                'default' => 0,
                'range'   => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ]
    ],
];
