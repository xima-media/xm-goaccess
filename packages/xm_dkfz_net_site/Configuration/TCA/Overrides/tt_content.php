<?php

$tempFields = [
    'tt_content_items' => [
        'label' => 'ITEMS',
        'config' => [
            'foreign_field' => 'foreign_uid',
            'foreign_sortby' => 'sorting',
            'foreign_table' => 'tt_content_item',
            'foreign_table_field' => 'foreign_table',
            'type' => 'inline',
            'minitems' => 0,
            'appearance' => [
                'collapseAll' => true,
                'expandSingle' => true,
                'useSortable' => true,
                'enabledControls' => [
                    'dragdrop' => true,
                    'info' => false,
                ],
            ],
        ],
    ],
    'color' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color',
        'description' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => \Xima\XmDkfzNetSite\Tca\TcaUtility::getItemsForColorField(
                true,
                ['LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:tt_content.color.0', '']
            ),
            'default' => '',
        ],
    ],
    'image' => [
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.images',
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', [
            'minitems' => 2,
            'maxitems' => 15,
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
            ],
            // custom configuration for displaying fields in the overlay/reference table
            // to use the imageoverlayPalette instead of the basicoverlayPalette
            'overrideChildTca' => [
                'types' => [
                    '0' => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                        'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                        'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                        'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                    ]
                ],
            ],
        ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
    ],
];

// Add all fields to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempFields);

// Add color to appearance tab of selected CTypes
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'color',
    'textmedia',
    'before:sectionIndex'
);

