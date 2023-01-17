<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Twitter client',
    'description' => 'Download and display tweets from Twitter',
    'category' => 'plugin',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.99.*',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Xima\\XimaTwitterClient\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'author' => 'Maik Schneider',
    'author_email' => 'maik.schneider@xima.de',
    'author_company' => 'XIMA Media GmbH',
    'version' => '1.0.2',
];
