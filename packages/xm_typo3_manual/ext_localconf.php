<?php

defined('TYPO3_MODE') || die();

(function ($extKey = 'xm_typo3_manual') {
    /**
     * Add ToolbarItems
     */
    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1624021395] = \Xima\XmTypo3Manual\Backend\ToolbarItems\DocumentationToolbarItem::class;
})();
