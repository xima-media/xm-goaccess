<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'XmDkfzNetJobs',
    'LatestJobs',
    'LLL:EXT:xm_dkfz_net_jobs/Resources/Private/Language/locallang.xlf:latestJobs.title',
    'EXT:xm_dkfz_net_jobs/Resources/Public/Images/plugin-job-listing.svg'
);

ExtensionUtility::registerPlugin(
    'XmDkfzNetJobs',
    'ListJobs',
    'LLL:EXT:xm_dkfz_net_jobs/Resources/Private/Language/locallang.xlf:listJobs.title',
    'EXT:xm_dkfz_net_jobs/Resources/Public/Images/plugin-job-listing.svg'
);

ExtensionUtility::registerPlugin(
    'XmDkfzNetJobs',
    'SearchJobs',
    'LLL:EXT:xm_dkfz_net_jobs/Resources/Private/Language/locallang.xlf:searchJobs.title',
    'EXT:xm_dkfz_net_jobs/Resources/Public/Images/plugin-job-listing.svg'
);
