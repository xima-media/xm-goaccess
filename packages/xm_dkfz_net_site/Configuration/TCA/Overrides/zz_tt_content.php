<?php

// Add color to appearance tab of selected CTypes
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'color',
    'textmedia, infobox',
    'after:layout'
);
