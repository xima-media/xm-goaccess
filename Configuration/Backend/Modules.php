<?php

return [
    'system_goaccess' => [
        'parent' => 'system',
        'position' => ['after' => 'log'],
        'access' => 'admin',
        'path' => '/module/system/goaccess',
        'iconIdentifier' => 'module-goaccess',
        'labels' => 'LLL:EXT:xm_goaccess/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => \Xima\XmGoaccess\Controller\BackendController::class . '::indexAction',
            ],
        ],
        'moduleData' => [
            'language' => 0,
        ],
    ],
];
