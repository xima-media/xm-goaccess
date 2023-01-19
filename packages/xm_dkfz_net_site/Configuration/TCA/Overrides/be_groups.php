<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['be_groups']['ctrl']['label'] = 'dkfz_number';
$GLOBALS['TCA']['be_groups']['ctrl']['label_alt'] = 'title';
$GLOBALS['TCA']['be_groups']['ctrl']['label_alt_force'] = true;
$GLOBALS['TCA']['be_groups']['ctrl']['searchFields'] .= ',dkfz_number';

ExtensionManagementUtility::addTCAcolumns('be_groups', [
    'dkfz_number' => [
        'label' => 'DKFZ number',
        'config' => [
            'type' => 'input'
        ]
    ]
]);
