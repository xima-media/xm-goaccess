<?php

// Register Page- and UserTS config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/Page.tsconfig"');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/User.tsconfig"');

//
Mfc\OAuth2\ResourceServer\Registry::addServer(
    'gitlab',
    'Login with GitLab',
    \Mfc\OAuth2\ResourceServer\GitLab::class,
    [
        'enabled'   => true,
        'arguments' => [
            'appId'                => '937dec2c36217253918ad7bc80003222d6a6bae97920afcff1990cf0f9b04994',
            'appSecret'            => '4a53040fbf5d503feb29339e441797fa51aa217f815b4176f4bfa23cbb5497eb',
            'gitlabServer'         => 'https://t3-gitlab-dev.xima.local', // Your GitLab Server
            'gitlabAdminUserLevel' => \Mfc\OAuth2\ResourceServer\GitLab::USER_LEVEL_DEVELOPER, // User level at which the user will be given admin permissions
            'gitlabDefaultGroups'  => '0', // Groups to assign to the User (comma separated list possible)
            'gitlabUserOption'     => 0, // UserConfig
            'blockExternalUser'    => false, // Blocks users with flag external from access the backend
            'projectName'          => 'dkfz/dkfz-t3-intranet', // the repository from which user information is fetched
            'verify'               => false,
        ],
    ]
);
