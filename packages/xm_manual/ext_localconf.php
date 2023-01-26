<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\Page\TreeController::class] = [
    'className' => Xima\XmManual\Controller\TreeController::class,
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    'options.pageTree.doktypesToShowInNewPageDragArea := addToList(701)'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import \'EXT:xm_manual/Configuration/TSconfig/Page.tsconfig\''
);

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->loadRequireJsModule('TYPO3/CMS/XmManual/ManualGlobal');

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['manual'] = 'EXT:xm_manual/Configuration/RTE/Manual.yaml';
