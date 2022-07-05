<?php

// Register Page- and UserTS config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/Page.tsconfig"');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/User.tsconfig"');

// Register DataHandler Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['xm_dkfz_net_site'] =
    \Xima\XmDkfzNetSite\Hook\DataHandlerHook::class;

// Register DrawHeader Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] = \Xima\XmDkfzNetSite\Hook\DrawHeaderHook::class . '->addPageInfos';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1657005026] = [
    'nodeName' => 'overrideToggle',
    'priority' => 40,
    'class' => \Xima\XmDkfzNetSite\Form\Element\OverrideToggleElement::class,
];
