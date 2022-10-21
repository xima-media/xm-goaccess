<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get( 'ke_search_premium', 'enableDistanceSearch')
) {
    $GLOBALS['TCA']['tx_kesearch_filters']['columns']['rendertype']['config']['items'][] = array(
        'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang.xlf:customcategory.distancesearch',
        'distance'
    );
    $GLOBALS['TCA']['tx_kesearch_filters']['types']['distance'] = array(
        'showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden'
            . ';;1;;1-1-1, title;;;;2-2-2, rendertype;;;;3-3-3, wrap;;;;4-4-4,'
    );
}
