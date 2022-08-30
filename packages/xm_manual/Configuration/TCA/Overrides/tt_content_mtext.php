<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:xm_manual/Resources/Private/Language/locallang.xlf:mtext',
        'tt_content_mtext',
        'content-beside-text-img-below-center',
    ],
    'image',
    'after'
);
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['tt_content_mtext'] = 'content-beside-text-img-below-center';

$GLOBALS['TCA']['tt_content']['palettes']['tt_content_mtext'] = [
    'label' => 'LLL:EXT:xm_manual/Resources/Private/Language/locallang.xlf:mtext.palette',
    'showitem' => 'header,--linebreak--,bodytext,--linebreak--,assets',
];

$GLOBALS['TCA']['tt_content']['types']['tt_content_mtext'] = [
    'showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    --palette--;;tt_content_mtext,
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
