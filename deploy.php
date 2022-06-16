<?php

namespace Deployer;

use function PHPUnit\Framework\throwException;
use Deployer\Exception\GracefulShutdownException;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

set('repository', 'git@t3-gitlab-dev.xima.local:dkfz/dkfz-t3-intranet.git');

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
    ->set('public_urls', ['https://stage.dkfz-typo3-dev.xima.local'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '0770')
    ->set('bin/composer', '/usr/local/bin/composer')
    ->set('bin/php', '/usr/bin/php')
    ->set('deploy_path', '/var/www/html/stage.dkfz-typo3-dev.xima.local');

host('feature')
    ->hostname('192.168.2.41')
    ->stage('feature')
    ->user('xima')
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '0770')
    ->set('bin/composer', '/usr/local/bin/composer')
    ->set('bin/php', '/usr/bin/php')
    ->set('public_urls', ['https://fbd.dkfz-typo3-dev.xima.local'])
    ->set('deploy_path', '/var/www/html/fbd.dkfz-typo3-dev.xima.local');

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

task('deploy:prepare:feature', function () {

    $featureRootPath = get('deploy_path');
    $branch = get('branch');

    // override path & public url
    set('deploy_path', $featureRootPath . '/' . $branch);
    set('public_urls', array_map(function ($url) use ($branch) {
        return $url . '/' . $branch . '/current/public';
    }, get('public_urls')));

    // abort if feature branch has already been configured
    if (test('[ -f {{deploy_path}}/.dep/releases.extended ]')) {
        return;
    }

    run('mkdir -p {{ deploy_path }}');
    run('mkdir -p {{ deploy_path }}/shared');

    // look for .env file
    if (test('[ ! -f ' . $featureRootPath . '/.env ]')) {
        throw new GracefulShutdownException('No .env file found in "' . $featureRootPath . '/"');
    }

    // copy .env file
    run('cp ' . $featureRootPath . '/.env {{deploy_path}}/shared/');

    // set database name
    run('echo "TYPO3_CONF_VARS__DB__Connections__Default__dbname=\'' . $branch . '\'" >> {{deploy_path}}/shared/.env');

    // set TYPO3_BASE domain
    foreach (get('public_urls') as $url) {
        run('echo "TYPO3_BASE=\'' . $url . '\'" >> {{deploy_path}}/shared/.env');
    }

    // create empty database
    $dbUser = run('grep TYPO3_CONF_VARS__DB__Connections__Default__user {{deploy_path}}/shared/.env | cut -d "=" -f2 | cut -c2- | rev | cut -c2- | rev');
    $dbHost = run('grep TYPO3_CONF_VARS__DB__Connections__Default__host {{deploy_path}}/shared/.env | cut -d "=" -f2 | cut -c2- | rev | cut -c2- | rev');
    $dbPassword = run('grep TYPO3_CONF_VARS__DB__Connections__Default__password {{deploy_path}}/shared/.env | cut -d "=" -f2 | cut -c2- | rev | cut -c2- | rev');
    $sqlStatement = 'CREATE DATABASE IF NOT EXISTS ' . $branch;
    run('echo "' . $sqlStatement . '" | mariadb -u ' . $dbUser . ' -h ' . $dbHost . ' --password="' . $dbPassword . '"');

})->onStage('feature');

task('db:prepare', function () {


})->onStage('feature');

before('deploy:prepare', 'deploy:prepare:feature');
before('db:truncate', 'db:prepare');
