<?php
defined('TYPO3_MODE') || die();

call_user_func(function () {
    /**
     * Temporary variables
     */
    $table = 'sys_file_reference';

    /**
     * Add references to sys_file_reference with type video
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
        $table,
        'videoOverlayPalette',
        '--linebreak--,displayInformation,--linebreak--,video_preview_image,--linebreak--,video_sign_language_video,--linebreak--,video_subtitles_file',
        'after:autoplay'
    );

    /**
     * Extend "sys_file_reference"
     */
    $tmp_additional_fields = [
        'video_preview_image' => [
            'label' => 'LLL:EXT:xm_dkfz_net_site/Resources/Private/Language/locallang.xlf:sys_file_reference.video_preview_image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'video_preview_image',
                [
                    'maxitems' => 1,
                    'appearance' => [
                        'fileUploadAllowed' => false,
                    ],
                    // it's necessary to add the types entry in overrideChildTca to enable expanding of the nested file references:
                    'overrideChildTca' => [
                        'types' => [
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '--palette--;;imageoverlayPalette, --palette--;;filePalette',
                            ],
                        ],
                    ],
                ],
                'jpg,jpeg,png'
            )
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $tmp_additional_fields);
});
