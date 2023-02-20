<?php

// Register plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'XmDkfzNetEvents',
    'LatestEvents',
    [
        Xima\XmDkfzNetEvents\Controller\EventController::class => 'latest',
    ],
    []
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'XmDkfzNetEvents',
    'ListEvents',
    [
        Xima\XmDkfzNetEvents\Controller\EventController::class => 'list',
    ],
    []
);
