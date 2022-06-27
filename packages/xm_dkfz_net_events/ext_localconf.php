<?php

// Register cache
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetevents_eventcache'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetevents_eventcache']['backend'] ??= \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;

// Register plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'XmDkfzNetEvents',
    'LatestEvents',
    [
        Xima\XmDkfzNetEvents\Controller\EventController::class => 'latest',
    ],
    []
);
