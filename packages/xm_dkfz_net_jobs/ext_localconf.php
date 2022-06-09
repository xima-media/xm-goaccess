<?php

// Register cache
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetjobs_jobcache'] ??= [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['xmdkfznetjobs_jobcache']['backend'] ??= \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class;
