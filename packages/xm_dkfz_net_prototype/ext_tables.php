<?php

defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'XmDkfzNetPrototype',
        'help',
        'prototype',
        '',
        [
            \Xima\XmDkfzNetPrototype\Controller\PrototypeController::class => 'index',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:xm_dkfz_net_prototype/Resources/Public/Images/module.svg',
            'labels' => 'LLL:EXT:xm_dkfz_net_prototype/Resources/Private/Language/locallang.xlf',
        ]
    );
}
