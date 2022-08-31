<?php

(function () {
    $GLOBALS['PAGES_TYPES'][701] = [
        'type' => 'web',
        'allowedTables' => 'pages,tt_content,sys_template,sys_file_reference',
    ];

    $GLOBALS['TBE_STYLES']['skins']['xm_manual']['stylesheetDirectories'][] = 'EXT:xm_manual/Resources/Public/Css/Backend/';

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'XmManual',
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
})();
