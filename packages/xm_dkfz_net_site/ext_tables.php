<?php

defined('TYPO3_MODE') || die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tt_content_item');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages(
        'tx_xmdkfznetsite_domain_model_disruptor'
    );
})();
