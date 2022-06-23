<?php

use Xima\XmOauth\LoginProvider\OAuthLoginProvider;

$extensionConfig = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['xm_oauth'];

if ($extensionConfig['enableBackendLogin']) {
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1656000096] = [
        'provider' => OAuthLoginProvider::class,
        'sorting' => 25,
        'icon-class' => 'fa-sign-in',
        'label' => 'LLL:EXT:oauth2/Resources/Private/Language/locallang.xlf:login.link',
    ];
}
