<?php

if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['html_path']) && $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_goaccess']['html_path']) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'XmGoaccess',
        'system',
        'goaccess',
        '',
        [
            \Xima\XmGoaccess\Controller\BackendController::class => 'index',
        ],
        [
            'access' => 'admin',
            'iconIdentifier' => 'module-goaccess',
            'labels' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
}