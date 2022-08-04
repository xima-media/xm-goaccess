<?php

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
