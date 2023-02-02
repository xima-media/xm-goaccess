<?php

return [
    'frontend' => [
        'xima/redirect-after-logout' => [
            'target' => \Xima\XmDkfzNetSite\Middleware\RedirectAfterLogout::class,
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
    ],
];
