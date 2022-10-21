<?php
return array (
    'ctrl' => array(
        'title' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_synonym',
        'label' => 'searchphrase',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY searchphrase',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
        ),
        'iconfile' => 'EXT:ke_search_premium/Resources/Public/Media/icon_tx_kesearchpremium_synonym.gif',
    ),
    'interface' => array (
        'showRecordFieldList' => 'hidden,searchphrase,synonyms'
    ),
    'columns' => array (
        'hidden' => array (
            'exclude' => 1,
            'label'   => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_synonym.hidden',
            'config'  => array (
                'type'    => 'check',
                'default' => '0'
            )
        ),
        'searchphrase' => array (
            'exclude' => 1,
            'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_synonym.searchphrase',
            'config' => array (
                'type' => 'input',
                'size' => '30',
            )
        ),
        'synonyms' => array (
            'exclude' => 0,
            'label' => 'LLL:EXT:ke_search_premium/Resources/Private/Language/locallang_db.xlf:tx_kesearchpremium_synonym.synonyms',
            'config' => array (
                'type' => 'text',
                'cols' => '30',
                'rows' => '5',
            )
        ),
    ),
    'types' => array (
        '0' => array('showitem' => 'hidden;;1;;1-1-1, searchphrase, synonyms')
    ),
    'palettes' => array (
        '1' => array('showitem' => '')
    )
);
