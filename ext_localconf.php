<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/db_layout.php']['drawHeaderHook'][] = \Xima\XmGoaccess\Hook\DrawPageHeaderHook::class . '->addPageChart';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Backend\Template\Components\ButtonBar']['getButtonsHook'][] = \Xima\XmGoaccess\Hook\ChartButtonHook::class . '->addChartButton';
