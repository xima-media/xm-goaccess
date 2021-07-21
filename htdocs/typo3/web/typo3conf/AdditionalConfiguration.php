<?php
/**
 * Local development configuration
 */
if (\TYPO3\CMS\Core\Core\Environment::getContext()->isDevelopment()) {
    /**
     * Display errors
     */
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 1;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['trustedHostsPattern'] = '.*';

    /**
     * MailHog configuration for DDEV
     */
    if (array_key_exists('DDEV_TLD', $_SERVER) && 'ddev.site' === $_SERVER['DDEV_TLD']) {
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'smtp';
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] = 'localhost:1025';
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_sendmail_command'] = '';
    }
}

/**
 * Available Languages
 */
$availableLanguages = ['de'];
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lang']['availableLanguages'] = ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lang']['availableLanguages'])
    ? array_merge($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['lang']['availableLanguages'], $availableLanguages)
    : $availableLanguages;
