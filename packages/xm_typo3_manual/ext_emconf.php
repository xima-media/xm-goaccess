<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'XIMA TYPO3 Redakteurshandbuch',
    'description' => 'Basis Redakteurshandbuch für TYPO3-Projekte',
    'category' => 'doc',
    'author' => 'Opensource Team',
    'author_company' => 'XIMA MEDIA GmbH',
    'author_email' => 'opensource@xima.de',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '11.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.0.0-11.99.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Xima\\XmTypo3Manual\\' => 'Classes',
        ],
    ],
];
