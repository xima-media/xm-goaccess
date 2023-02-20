<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content_usertable.title',
        'usertable',
        'content-user',
    ],
    'uploads',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['usertable'] = 'content-user';

$GLOBALS['TCA']['tt_content']['types']['usertable'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;general,
                    --palette--;;header,
                    bodytext,
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
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
                'richtextConfiguration' => 'minimal',
            ],
        ],
        'tt_content_items' => [
            'label' => 'User',
            'config' => [
                'overrideChildTca' => [
                    'columns' => [
                        'record_type' => [
                            'config' => [
                                'default' => 'user-contact',
                            ],
                        ],
                        'text' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                            'config' => [
                                'placeholder' => '__row|fe_user|responsibilities',
                                'mode' => 'useOrOverridePlaceholder',
                            ],
                        ],
                        'overrides' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                        ],
                        'contacts' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                        ],
                        'overrides2' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                        ],
                        'overrides3' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                        ],
                        'fe_users' => [
                            'displayCond' => 'FIELD:fe_user:REQ:true',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

// Register custom Preview
$GLOBALS['TCA']['tt_content']['types']['usertable']['previewRenderer'] = \Xima\XmDkfzNetSite\Preview\UsertablePreviewRenderer::class;
