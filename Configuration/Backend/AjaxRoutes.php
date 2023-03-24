<?php

return [
    'goaccess_page' => [
        'path' => '/goaccess/page',
        'access' => 'public',
        'target' => \Xima\XmGoaccess\Controller\BackendController::class . '::pageChartAction',
    ],
    'goaccess_settings' => [
        'path' => '/goaccess/settings',
        'access' => 'public',
        'target' => \Xima\XmGoaccess\Controller\BackendController::class . '::userSettingsAction',
    ],
];
