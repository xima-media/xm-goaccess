<?php

return [
    'goaccess_page' => [
        'path' => '/goaccess/page',
        'access' => 'public',
        'target' => \Xima\XmGoaccess\Controller\BackendController::class . '::pageChartAction',
    ],
];
