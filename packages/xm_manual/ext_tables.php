<?php

(function () {

    $GLOBALS['PAGES_TYPES'][701] = [
        'type' => 'web',
        'allowedTables' => '*',
    ];

    if (TYPO3_MODE === 'BE') {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'XmManal',
            'help',
            'manual',
            '',
            [
                \Xima\XmManual\Controller\ManualController::class => 'index',
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:xm_manual/Resources/Public/Icons/icon-module.svg',
                'labels' => 'LLL:EXT:xm_manual/Resources/Private/Language/locallang.xlf',
                'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
            ]
        );
    }
})();
