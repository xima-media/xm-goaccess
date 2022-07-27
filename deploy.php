<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

set('repository', 'git@t3-gitlab-dev.xima.local:dkfz/dkfz-t3-intranet.git');

host('local')
    ->hostname('local')
    ->set('deploy_path', getcwd())
    ->set('public_urls', ['https://xm-dkfz-net.ddev.site']);

host('staging')
    ->hostname('192.168.2.41')
    ->stage('staging')
    ->user('xima')
    ->set('branch', 'master')
    ->set('public_urls', ['https://stage.dkfz-typo3-dev.xima.local'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_recursive', false)
    ->set('writable_chmod_mode', '2770')
    ->set('deploy_path', '/var/www/html/stage.dkfz-typo3-dev.xima.local');

host('staging-dkfz')
    ->hostname('intracmsstage')
    ->stage('staging-dkfz')
    ->user('xima')
    ->set('branch', 'master')
    ->set('repository', 'git@git.dkfz.de:dkfz/dkfz-t3-intranet.git')
    ->set('public_urls', ['https://intracmsstage.dkfz-heidelberg.de'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_recursive', false)
    ->set('writable_chmod_mode', '2770')
    ->set('deploy_path', '/var/www/html/intracmsstage.dkfz-heidelberg.de')
    ->set('fetch_method', 'curl');

host('feature')
    ->hostname('192.168.2.41')
    ->stage('feature')
    ->user('xima')
    ->set('db_source_host', 'staging')
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '2770')
    ->set('writable_chmod_recursive', false)
    ->set('public_urls', ['https://fbd.dkfz-typo3-dev.xima.local'])
    ->set('deploy_path', '/var/www/html/fbd.dkfz-typo3-dev.xima.local');

after('deploy:update_code', 'deploy:upload-dist');

task('deploy:upload-dist', function () {
    upload(
        'packages/xm_dkfz_net_prototype/Resources/Public/',
        '{{release_path}}/packages/xm_dkfz_net_prototype/Resources/Public/'
    );
});
