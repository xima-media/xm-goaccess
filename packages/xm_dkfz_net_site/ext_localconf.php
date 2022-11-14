<?php

// Register Page- and UserTS config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/User.tsconfig"');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/Page.tsconfig"');

// Register DataHandler Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['xm_dkfz_net_site'] =
    \Xima\XmDkfzNetSite\Hook\DataHandlerHook::class;

// Register DrawHeader Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] = \Xima\XmDkfzNetSite\Hook\DrawHeaderHook::class . '->addPageInfos';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawFooterHook'][] = \Xima\XmDkfzNetSite\Hook\DrawFooterHook::class . '->addPageInfos';

// Register ke_search Hooks
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyFileIndexEntryFromContentIndexer'][1667832752] = \Xima\XmDkfzNetSite\Hook\KeSearch\ModifyFileIndexEntryHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyPagesIndexEntry'][1668072533] = \Xima\XmDkfzNetSite\Hook\KeSearch\ModifyPagesIndexEntryHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyPageContentFields'][1667922623] = \Xima\XmDkfzNetSite\Hook\KeSearch\ModifyContentFromContentElementHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyContentFromContentElement'][1667905089] = \Xima\XmDkfzNetSite\Hook\KeSearch\ModifyContentFromContentElementHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['modifyExtNewsIndexEntry'][1668072533] = \Xima\XmDkfzNetSite\Hook\KeSearch\ModifyExtNewsIndexEntryHook::class;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ke_search']['additionalResultMarker'][1668005137] = \Xima\XmDkfzNetSite\Hook\KeSearch\AdditionalResultMarkerHook::class;

// Register Form Element
$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1657005026] = [
    'nodeName' => 'overrideToggle',
    'priority' => 40,
    'class' => \Xima\XmDkfzNetSite\Form\Element\OverrideToggleElement::class,
];

// Register RTE Presets
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['default'] = 'EXT:xm_dkfz_net_site/Configuration/RTE/DkfzNetDefault.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['minimal'] = 'EXT:xm_dkfz_net_site/Configuration/RTE/DkfzNetMinimal.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['extended'] = 'EXT:xm_dkfz_net_site/Configuration/RTE/DkfzNetExtended.yaml';
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['disruptor'] = 'EXT:xm_dkfz_net_site/Configuration/RTE/DkfzNetDisruptor.yaml';

// Register NewsRepository override
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\GeorgRinger\News\Domain\Repository\NewsRepository::class] = [
    'className' => Xima\XmDkfzNetSite\Domain\Repository\NewsRepository::class,
];
// Register User override
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Blueways\BwGuild\Domain\Model\User::class] = [
    'className' => Xima\XmDkfzNetSite\Domain\Model\User::class,
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\Blueways\BwGuild\Domain\Model\Dto\UserDemand::class] = [
    'className' => Xima\XmDkfzNetSite\Domain\Model\Dto\UserDemand::class,
];
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
    ->registerImplementation(
        Blueways\BwGuild\Domain\Model\User::class,
        \Xima\XmDkfzNetSite\Domain\Model\User::class
    );
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
    ->registerImplementation(
        Blueways\BwGuild\Domain\Model\Dto\UserDemand::class,
        \Xima\XmDkfzNetSite\Domain\Model\Dto\UserDemand::class
    );

// change order of backend login provider
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders']['1616569531']['sorting'] = 75;
