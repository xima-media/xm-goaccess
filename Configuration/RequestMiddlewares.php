<?php
return [
    'frontend' => [
        'Tpwd/KeSearchPremium/SearchApi' => [
            'target' => \Tpwd\KeSearchPremium\Middleware\HeadlessApiMiddleware::class,
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ],
        ],
    ],
];