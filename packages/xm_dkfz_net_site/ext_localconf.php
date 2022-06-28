<?php

// Register Page- and UserTS config
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/Page.tsconfig"');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('@import "EXT:xm_dkfz_net_site/Configuration/TSconfig/User.tsconfig"');

$extConf = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_dkfz_net_site'];

if ($extConf['gitlabLoginEnabled']) {
    Mfc\OAuth2\ResourceServer\Registry::addServer(
        'gitlab',
        'Login with GitLab',
        \Mfc\OAuth2\ResourceServer\GitLab::class,
        [
            'enabled' => true,
            'arguments' => [
                'appId' => $extConf['gitlabAppId'],
                'appSecret' => $extConf['gitlabAppSecret'],
                'gitlabServer' => 'https://t3-gitlab-dev.xima.local',
                'gitlabAdminUserLevel' => \Mfc\OAuth2\ResourceServer\GitLab::USER_LEVEL_DEVELOPER,
                'gitlabDefaultGroups' => '0',
                'gitlabUserOption' => 0,
                'blockExternalUser' => false,
                'projectName' => 'dkfz/dkfz-t3-intranet',
                'verify' => false,
            ],
        ]
    );
}

// Register DataHandler Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['xm_dkfz_net_site'] =
    \Xima\XmDkfzNetSite\Hook\DataHandlerHook::class;
