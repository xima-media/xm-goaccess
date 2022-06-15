<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

set('repository', 'git@t3-gitlab-dev.xima.local:dkfz/xm-dkfz-net.git');

host('local')
    ->hostname('local')
    ->set('deploy_path', getcwd())
    ->set('public_urls', ['https://xm-dkfz-net.ddev.site']);

set('writable_dirs', function () {
    return [
        get('web_path') . 'typo3conf',
        get('web_path') . 'typo3temp',
        get('web_path') . 'uploads',
        get('web_path') . 'fileadmin',
        get('web_path') . '../var',
    ];
});

host('staging')
    ->hostname('192.168.2.41')
    ->stage('staging')
    ->user('xima')
    ->set('branch', 'master')
    ->set('public_urls', ['https://dkfz-typo3-dev.xima.local'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '0770')
    ->set('bin/composer', '/usr/local/bin/composer')
    ->set('bin/php', '/usr/bin/php')
    ->set('deploy_path', '/var/www/html/dkfz-typo3-dev/typo3-staging');

after('deploy:update_code', 'deploy:upload-dist');

task('deploy:upload-dist', function () {
    upload(
        'packages/xm_dkfz_net_prototype/Resources/Public/',
        '{{release_path}}/packages/xm_dkfz_net_prototype/Resources/Public/'
    );
});

after('deploy:shared', 'deploy:fix-shared-permissions');

task('deploy:fix-shared-permissions', function () {
    foreach (get('shared_dirs') ?? [] as $dir) {
        run('find {{deploy_path}}/shared/' . $dir . ' -type d -exec chmod ' . get('writable_chmod_mode') . ' {} +');
    }
});
