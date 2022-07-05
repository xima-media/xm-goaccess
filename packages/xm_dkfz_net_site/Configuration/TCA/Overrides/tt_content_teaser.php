<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'Teaser',
        'teaser',
        'content-listgroup',
    ],
    'image',
    'after'
);

$GLOBALS['TCA']['tt_content']['types']['teaser'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    tt_content_items,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                    --palette--;;frames,
                    --palette--;;appearanceLinks,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    rowDescription,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,',
    'columnsOverrides' => [
        'tt_content_items' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_teaser.items',
            'config' => [
                'overrideChildTca' => [
                    'columns' => [
                        'record_type' => [
                            'config' => [
                                'default' => 'teaser-item',
                            ],
                        ],
                        'link' => [
                            'config' => [
                                'eval' => 'required',
                                'fieldControl' => [
                                    'linkPopup' => [
                                        'options' => [
                                            'blindLinkFields' => 'class,params,target,title',
                                            'blindLinkOptions' => 'file,folder,mail,telephone,url',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
