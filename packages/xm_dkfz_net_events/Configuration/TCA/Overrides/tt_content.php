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

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['xmdkfznetevents_latestevents'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'xmdkfznetevents_latestevents',
    'FILE:EXT:xm_dkfz_net_events/Configuration/FlexForms/List.xml'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['xmdkfznetevents_listevents'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'xmdkfznetevents_listevents',
    'FILE:EXT:xm_dkfz_net_events/Configuration/FlexForms/List.xml'
);
