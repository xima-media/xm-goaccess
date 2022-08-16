<?php

return [
    'BE' => [
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
        'extensionmanager' => [
            'automaticInstallation' => '1',
            'offlineMode' => '0',
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
                'gitlab' => [
                    'description' => 'Login with Gitlab',
                    'iconIdentifier' => 'oauth2-gitlab',
                    'label' => 'Gitlab',
                    'options' => [
                        'clientId' => '937dec2c36217253918ad7bc80003222d6a6bae97920afcff1990cf0f9b04994',
                        'clientSecret' => '4a53040fbf5d503feb29339e441797fa51aa217f815b4176f4bfa23cbb5497eb',
                        'scopeSeparator' => ' ',
                        'scopes' => [
                            'openid',
                            'read_user',
                        ],
                        'urlAccessToken' => 'https://t3-gitlab-dev.xima.local/oauth/token',
                        'urlAuthorize' => 'https://t3-gitlab-dev.xima.local/oauth/authorize',
                        'urlResourceOwnerDetails' => 'https://t3-gitlab-dev.xima.local/api/v4/user',
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
        'xm_dkfz_net_jobs' => [
            'api_url' => 'https://jobs.dkfz.de/jobPublication/list.json?language=de',
        ],
        'xm_goaccess' => [
            'html_path' => '../var/goaccess/goaccess.html',
        ],
        'xm_dkfz_net_site' => [
            'phone_book_api_url' => 'https://info.dkfz-heidelberg.de/telefonbuch/api/db',
        ],
    ],
    'FE' => [
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
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                ],
                'imagesizes' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pages' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pagesection' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'rootline' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
            ],
        ],
        'fileCreateMask' => '0660',
        'folderCreateMask' => '2770',
        'lockSSL' => 2,
        'devIPmask' => '',
        'displayErrors' => 0,
        'encryptionKey' => 'c8485f56f13ae2690459c8bf7fc2c5dfa9f49d6ae5cf54ad0c66bf1f1a2f752f1c86269e1bfca18ef4fd69dbe5ec21bf',
        'exceptionalErrors' => 4096,
        'features' => [
            'unifiedPageTranslationHandling' => true,
            'yamlImportsFollowDeclarationOrder' => true,
        ],
        'sitename' => 'DKFZ Intranet',
        'systemLocale' => 'de_DE.UTF-8',
        'systemMaintainers' => [
            1,
            2,
        ],
    ],
];
