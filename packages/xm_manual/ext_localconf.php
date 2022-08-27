<?php

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\Page\TreeController::class] = [
    'className' => Xima\XmManual\Controller\TreeController::class,
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    'options.pageTree.doktypesToShowInNewPageDragArea := addToList(701)'
);

$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
$pageRenderer->loadRequireJsModule('TYPO3/CMS/XmManual/ManualGlobal');
