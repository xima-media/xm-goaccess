<?php

defined('TYPO3') || die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_content_item');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_xmdkfznetsite_domain_model_disruptor'
    );
})();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'XmDkfzNetSite',
    'system',
    'tx_Dkfz',
    '',
    [
        \Xima\XmDkfzNetSite\Controller\DkfzController::class => 'index',
    ],
    [
        'access' => 'admin',
        'iconIdentifier' => 'module-dkfz',
        'labels' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang_mod.xlf',
    ]
);

$GLOBALS['TBE_STYLES']['skins']['xm_dkfz_net_site']['stylesheetDirectories'][] = 'EXT:xm_dkfz_net_site/Resources/Public/Css/Backend/';
