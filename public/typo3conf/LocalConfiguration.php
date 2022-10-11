<?php

return [
    'BE' => [
        'compressionLevel' => 9,
        'cookieSameSite' => 'lax',
        'debug' => false,
        'explicitADmode' => 'explicitAllow',
        'installToolPassword' => '$argon2i$v=19$m=65536,t=16,p=1$QlV4RDFVeldOQ01RZElTMg$hb2jOB04prE4zCBrKBJA6DTXYzrN9Jkh39J6UizIztM',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'driver' => 'mysqli',
            ],
        ],
    ],
    'EXTCONF' => [
        'lang' => [
            'availableLanguages' => [
                'de',
            ],
        ],
    ],
    'EXTENSIONS' => [
        'backend' => [
            'backendFavicon' => '',
            'backendLogo' => '',
            'loginBackgroundImage' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/backend_login_bg.jpeg',
            'loginFootnote' => '',
            'loginHighlightColor' => '#0047b9',
            'loginLogo' => 'EXT:xm_dkfz_net_site/Resources/Public/Images/backend_login_logo.svg',
            'loginLogoAlt' => '',
        ],
        'bw_icons' => [
            'pages' => '0',
            'sys_category' => '0',
            'tt_content' => '0',
        ],
        'crawler' => [
            'cleanUpOldQueueEntries' => '1',
            'cleanUpProcessedAge' => '2',
            'cleanUpScheduledAge' => '7',
            'countInARun' => '100',
            'crawlHiddenPages' => '0',
            'enableTimeslot' => '1',
            'frontendBasePath' => '/',
            'makeDirectRequests' => '0',
            'maxCompileUrls' => '10000',
            'phpBinary' => 'php',
            'phpPath' => '',
            'processDebug' => '0',
            'processLimit' => '1',
            'processMaxRunTime' => '300',
            'processVerbose' => '0',
            'purgeQueueDays' => '14',
            'sleepAfterFinish' => '10',
            'sleepTime' => '1000',
        ],
        'extensionmanager' => [
            'automaticInstallation' => '1',
            'offlineMode' => '0',
        ],
        'ke_search' => [
            'allowEmptySearch' => '1',
            'enableExplicitAnd' => '0',
            'enablePartSearch' => '1',
            'finishNotification' => '0',
            'loglevel' => 'ERROR',
            'multiplyValueToTitle' => '1',
            'notificationRecipient' => '',
            'notificationSender' => 'no_reply@domain.com',
            'notificationSubject' => '[KE_SEARCH INDEXER NOTIFICATION]',
            'pathCatdoc' => '/usr/bin/',
            'pathPdfinfo' => '/usr/bin/',
            'pathPdftotext' => '/usr/bin/',
            'searchWordLength' => '4',
        ],
        'luxletter' => [
            'addTypeNumToNumberLocation' => '1562349004',
            'embedImagesInNewsletter' => '1',
            'limitToContext' => '',
            'multiLanguageMode' => '0',
            'receiverAction' => '1',
            'rewriteLinksInNewsletter' => '1',
        ],
        'news' => [
            'advancedMediaPreview' => '1',
            'archiveDate' => 'date',
            'categoryBeGroupTceFormsRestriction' => '0',
            'categoryRestriction' => '',
            'contentElementPreview' => '1',
            'contentElementRelation' => '1',
            'dateTimeNotRequired' => '0',
            'hidePageTreeForAdministrationModule' => '0',
            'manualSorting' => '0',
            'mediaPreview' => 'false',
            'prependAtCopy' => '1',
            'resourceFolderImporter' => '/news_import',
            'rteForTeaser' => '0',
            'showAdministrationModule' => '1',
            'showImporter' => '0',
            'slugBehaviour' => 'unique',
            'storageUidImporter' => '1',
            'tagPid' => '1',
        ],
        'oauth2_client' => [
            'providers' => [
                'dkfz' => [
                    'description' => 'DKFZ OAuth Login',
                    'iconIdentifier' => 'dkfz-d',
                    'label' => 'Im Backend anmelden',
                    'implementationClassName' => \Xima\XmDkfzNetSite\Client\Provider\Dkfz::class,
                    'options' => [
                        'clientId' => '8086662d-c0d6-4daa-b154-8f6524bbf6a9',
                        'clientSecret' => 'k_2XzWbsGYKaE2tgIUIOK6_FpQogkmZSVAKUYW3K',
                        'scopeSeparator' => ' ',
                        'scopes' => [
                            'openid',
                            'email',
                            'profile',
                        ],
                        'urlAccessToken' => 'https://tdkfzadfs.dkfz-heidelberg.de/adfs/oauth2/token',
                        'urlAuthorize' => 'https://tdkfzadfs.dkfz-heidelberg.de/adfs/oauth2/authorize',
                    ],
                    'scopes' => [
                        'backend',
                        'frontend',
                    ],
                ],
                'xima' => [
                    'description' => 'Login with XIMA',
                    'iconIdentifier' => 'xima-x',
                    'label' => 'XIMA',
                    'options' => [
                        'clientId' => '14b07207-5728-4453-a6a6-5539803724cb',
                        'clientSecret' => 'VcU8Q~21xmtapRPT24ZlK4itF~x0B1So2jEfOcWs',
                        'scopeSeparator' => ' ',
                        'scopes' => [
                            'openid',
                            'profile',
                        ],
                        'urlAccessToken' => 'https://login.microsoftonline.com/890938ce-3232-42b7-981d-9a7cbe37a475/oauth2/v2.0/token',
                        'urlAuthorize' => 'https://login.microsoftonline.com/890938ce-3232-42b7-981d-9a7cbe37a475/oauth2/v2.0/authorize',
                        'urlResourceOwnerDetails' => 'https://graph.microsoft.com/oidc/userinfo',
                    ],
                    'scopes' => [
                        'backend',
                        'frontend',
                    ],
                ],
            ],
        ],
        'scheduler' => [
            'maxLifetime' => '1440',
            'showSampleTasks' => '1',
        ],
        'xm_dkfz_net_events' => [
            'api_url_override' => '/fileadmin/rss.ashx',
        ],
        'xm_dkfz_net_jobs' => [
            'api_url' => 'https://jobs.dkfz.de/jobPublication/list.json?language=de',
        ],
        'xm_dkfz_net_site' => [
            'default_be_user_group' => '1',
            'default_fe_user_group' => '1',
            'phone_book_api_url' => '../var/phonebook/db.json',
            'storage_identifier_for_imported_groups' => '1:Gruppen',
        ],
        'xm_goaccess' => [
            'html_path' => '../var/goaccess/goaccess.html',
            'json_path' => '../var/goaccess/goaccess.json',
        ],
        'xm_kesearch_remote' => [
            'cache_dir' => '../var/xm_kesearch_remote',
        ],
    ],
    'FE' => [
        'compressionLevel' => 9,
        'cookieSameSite' => 'lax',
        'debug' => false,
        'disableNoCacheParameter' => true,
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'GFX' => [
        'imagefile_ext' => 'gif,png,jpeg,jpg,webp',
        'processor' => 'GraphicsMagick',
        'processor_allowTemporaryMasksAsPng' => false,
        'processor_colorspace' => 'RGB',
        'processor_effects' => false,
        'processor_enabled' => true,
        'processor_path' => '/usr/bin/',
        'processor_path_lzw' => '/usr/bin/',
    ],
    'LOG' => [
        'TYPO3' => [
            'CMS' => [
                'deprecations' => [
                    'writerConfiguration' => [
                        'notice' => [
                            'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                                'disabled' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'MAIL' => [
        'transport' => 'sendmail',
        'transport_sendmail_command' => '/usr/local/bin/mailhog sendmail test@example.org --smtp-addr 127.0.0.1:1025',
        'transport_smtp_encrypt' => '',
        'transport_smtp_password' => '',
        'transport_smtp_server' => '',
        'transport_smtp_username' => '',
    ],
    'SYS' => [
        'caching' => [
            'cacheConfigurations' => [
                'bwguild' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                ],
                'hash' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
                ],
                'imagesizes' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\SimpleFileBackend',
                    'options' => [
                        'compression' => '__UNSET',
                    ],
                ],
                'pages' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
                    'options' => [
                        'compression' => '__UNSET',
                    ],
                ],
                'pagesection' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
                    'options' => [
                        'compression' => '__UNSET',
                    ],
                ],
                'rootline' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\FileBackend',
                    'options' => [
                        'compression' => '__UNSET',
                    ],
                ],
            ],
        ],
        'devIPmask' => '',
        'displayErrors' => 0,
        'encryptionKey' => 'c8485f56f13ae2690459c8bf7fc2c5dfa9f49d6ae5cf54ad0c66bf1f1a2f752f1c86269e1bfca18ef4fd69dbe5ec21bf',
        'exceptionalErrors' => 4096,
        'features' => [
            'unifiedPageTranslationHandling' => true,
            'yamlImportsFollowDeclarationOrder' => true,
        ],
        'fileCreateMask' => '0660',
        'folderCreateMask' => '2770',
        'lockSSL' => 2,
        'sitename' => 'DKFZ Intranet',
        'systemLocale' => 'de_DE.UTF-8',
        'systemMaintainers' => [
            1,
            2,
        ],
    ],
];
