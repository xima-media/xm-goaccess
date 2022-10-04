<?php

return [
    'frontend' => [
        'xima/absolute-paths' => [
            'target' => \Xima\XmManual\Middleware\EncodeImagesBase64Middleware::class,
            'after' => [
                'typo3/cms-frontend/output-compression',
            ],
        ],
    ],
];
