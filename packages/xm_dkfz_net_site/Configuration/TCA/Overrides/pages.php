<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$ll = 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', [
    'tx_xmdkfznetsite_color' => [
        'exclude' => 1,
        'label' => $ll . 'pages.color',
        'description' => $ll . 'pages.color.description',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => \Xima\XmDkfzNetSite\Tca\TcaUtility::getItemsForColorField(),
            'default' => '',
        ],
    ],
]);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'pages',
    'layout',
    'tx_xmdkfznetsite_color,--linebreak--',
    'before:layout'
);
