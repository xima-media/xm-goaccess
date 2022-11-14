<?php

defined('TYPO3_MODE') || die();

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
