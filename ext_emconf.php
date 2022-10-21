<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Faceted Search Premium',
    'description' => 'This extension extends ke_search with a fast search index powered by Sphinx and other useful '.
        'features like distance search, auto-suggestion, did-you-mean and remote indexer.',
    'category' => 'fe',
    'author' => 'TPWD AG',
    'author_email' => 'ke_search@tpwd.de',
    'shy' => '',
    'dependencies' => 'ke_search',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'author_company' => 'TPWD AG',
    'version' => '4.2.1',
    'constraints' => array(
        'depends' => array(
            'typo3' => '10.4.0-11.5.99',
            'ke_search' => '4.5.0',
        ),
    ),
    'autoload' => array(
        'psr-4' => array('Tpwd\\KeSearchPremium\\' => 'Classes'),
        'classmap' => array('Classes'),
    ),
);
