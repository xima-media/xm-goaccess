<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'XmDkfzNetEvents',
    'LatestEvents',
    'LLL:EXT:xm_dkfz_net_events/Resources/Private/Language/locallang.xlf:latestEvents.title',
    'EXT:xm_dkfz_net_events/Resources/Public/Images/plugin-event-listing.svg'
);

ExtensionUtility::registerPlugin(
    'XmDkfzNetEvents',
    'ListEvents',
    'LLL:EXT:xm_dkfz_net_events/Resources/Private/Language/locallang.xlf:listEvents.title',
    'EXT:xm_dkfz_net_events/Resources/Public/Images/plugin-event-listing.svg'
);
