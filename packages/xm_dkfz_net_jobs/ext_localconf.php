<?php

// Register cache
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetjobs_jobcache'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetjobs_jobcache']['backend'] ??= \TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend::class;

// Register plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'XmDkfzNetJobs',
    'LatestJobs',
    [
            Xima\XmDkfzNetJobs\Controller\JobController::class => 'latest',
    ],
    []
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'XmDkfzNetJobs',
    'ListJobs',
    [
        Xima\XmDkfzNetJobs\Controller\JobController::class => 'list',
    ],
    []
);
