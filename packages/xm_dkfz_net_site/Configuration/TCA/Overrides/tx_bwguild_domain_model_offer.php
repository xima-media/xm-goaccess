<?php

$GLOBALS['TCA']['tx_bwguild_domain_model_offer']['columns']['description']['config']['enableRichtext'] = false;
$GLOBALS['TCA']['tx_bwguild_domain_model_offer']['columns']['is_public']['default'] = 0;

// disable translation for offers
unset($GLOBALS['TCA']['tx_bwguild_domain_model_offer']['ctrl']['languageField']);
