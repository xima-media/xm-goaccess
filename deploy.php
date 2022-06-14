<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

set('repository', 'git@t3-gitlab-dev.xima.local:dkfz/xm-dkfz-net.git');


host('local')
    ->hostname('local')
    ->set('deploy_path', getcwd())
    ->set('public_urls', ['https://xm-dkfz-net.ddev.site']);

host('staging')
    ->hostname('192.168.2.41')
    ->stage('staging')
    ->user('xima')
    ->set('branch', 'master')
    ->set('public_urls', ['https://dkfz-typo3-dev.xima.local'])
    ->set('http_user', 'www-data')
    ->set('bin/composer', '/usr/local/bin/composer')
    ->set('bin/php', '/usr/bin/php')
    ->set('deploy_path', '/var/www/html/dkfz-typo3-dev/typo3-staging');

after('deploy:update_code', 'deploy:upload-dist');

task('deploy:upload-dist', function () {
    upload('packages/xm_dkfz_net_prototype/Resources/Public', '{{release_path}}/xm_dkfz_net_prototype/Resources/Public');
});
