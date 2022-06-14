<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

host('local')
    ->hostname('local')
    ->set('deploy_path', getcwd())
    ->set('public_urls', ['https://xm-dkfz-net.ddev.site']);

host('staging')
    ->hostname('dkfz-typo3-dev.xima.local')
    ->stage('staging')
    ->user('xima')
    ->set('branch', 'master')
    ->set('public_urls', ['https://dkfz-typo3-dev.xima.local'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('bin/composer', '/usr/local/bin/composer')
    ->set('bin/php', '/usr/bin/php')
    ->set('deploy_path', '/var/www/html/dkfz-typo3-dev/typo3-staging');

