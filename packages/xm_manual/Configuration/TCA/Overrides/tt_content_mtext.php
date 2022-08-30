<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'fwfwef',
        'mtext',
        'content-beside-text-img-below-center',
    ],
    'image',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['mtext'] = 'content-beside-text-img-below-center';

$GLOBALS['TCA']['tt_content']['palettes']['mtext'] = [
    'label' => 'LLL:EXT:xm_manual/Resources/Private/Language/locallang.xlf:mtext.palette',
    'showitem' => 'header,--linebreak--,bodytext,--linebreak--,assets',
];

$GLOBALS['TCA']['tt_content']['types']['mtext'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;mtext,CType,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,colPos',
    'columnsOverrides' => [
        'bodytext' => [
            'config' => [
                'enableRichtext' => true,
            ],
        ],
    ],
];
