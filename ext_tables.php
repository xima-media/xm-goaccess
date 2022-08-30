<?php
defined('TYPO3_MODE') || die();

(static function () {
    /**
     * CSS skins for backend
     */
    $GLOBALS['TBE_STYLES']['skins']['xm_typo3_manual'] = [
        'name' => 'XIMA TYPO3 Manual Backend Skin',
        'stylesheetDirectories' => [
            'visual' => 'EXT:xm_typo3_manual/Resources/Public/Backend/Css/'
        ]
    ];
})();
