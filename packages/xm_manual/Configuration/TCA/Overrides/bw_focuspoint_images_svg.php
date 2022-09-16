<?php

$showItem = $GLOBALS['TCA']['tt_content']['types']['bw_focuspoint_images_svg']['showitem'];
$GLOBALS['TCA']['tt_content']['types']['bw_focuspoint_images_svg']['showitem'] = str_replace('assets,', 'bodytext,assets,', $showItem);

$GLOBALS['TCA']['tt_content']['types']['bw_focuspoint_images_svg']['columnsOverrides']['bodytext'] = [
    'config' => [
        'enableRichtext' => true,
        'richtextConfiguration' => 'manual',
    ],
];
