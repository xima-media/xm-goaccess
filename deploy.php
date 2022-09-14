<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

set('repository', 'git@git.xima.de:typo3/dkfz/xm-dkfz-net.git');
//set('teams_webhook', 'https://ximamediagmbh.webhook.office.com/webhookb2/a14869ed-d90e-419d-849e-b185ac6e5636@890938ce-3232-42b7-981d-9a7cbe37a475/IncomingWebhook/49bc7a84cd024f79b74984417341520c/f0210cd8-c97d-4d14-b199-28af55f9215e');

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
    ->set('fetch_method', 'curl')
    ->set('media_rsync_flags', '-rz --perms');

host('production-dkfz')
    ->hostname('intracmsprod')
    ->stage('production-dkfz')
    ->user('xima')
    ->set('branch', 'master')
    ->set('repository', 'git@git.dkfz.de:dkfz/dkfz-t3-intranet.git')
    ->set('public_urls', ['https://intracmsprod.dkfz-heidelberg.de'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_recursive', false)
    ->set('writable_chmod_mode', '2770')
    ->set('deploy_path', '/var/www/html/intracmsprod.dkfz-heidelberg.de')
    ->set('fetch_method', 'curl')
    ->set('media_rsync_flags', '-rz --perms');

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

// Upload of dist files
after('deploy:update_code', 'deploy:upload-dist');
task('deploy:upload-dist', function () {
    upload(
        'packages/xm_dkfz_net_prototype/Resources/Public/',
        '{{release_path}}/packages/xm_dkfz_net_prototype/Resources/Public/'
    );
});

// Cache warmup
task('typo3cms:cache:warmup', function () {
    $activePath = get('deploy_path') . '/' . (test('[ -L {{deploy_path}}/release ]') ? 'release' : 'current');
    run('cd ' . $activePath . ' && {{bin/php}} {{bin/typo3cms}} cache:warmup');
});
before('deploy:symlink', 'typo3cms:cache:warmup');

// Reset xima intern staging in pipeline
option(
    'DKFZ_ACCESS_TOKEN',
    null,
    \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
    'Gitlab token of DKFZ production repo.'
);
task('reset:from_production_artifact', function () {
    run('cd {{deploy_path}}/current && curl --location --output artifacts.zip --header "PRIVATE-TOKEN: {{DKFZ_ACCESS_TOKEN}}" "https://git.dkfz.de/api/v4/projects/69/jobs/artifacts/master/download?job=backup-production-dkfz"');
    if (test('[ -f {{deploy_path}}/current/artifacts.zip ]')) {
        run('cd {{deploy_path}}/current && vendor/bin/dep db:rmdump --options=dumpcode:BackupProductionDkfz --no-interaction -vvv');
        run('cd {{deploy_path}}/current && unzip -o artifacts.zip');
        run('mv -f {{deploy_path}}/current/.dep/database/dumps/* {{deploy_path}}/.dep/database/dumps/');
        run('cd {{deploy_path}}/current && vendor/bin/dep db:decompress --options=dumpcode:BackupProductionDkfz --no-interaction -vvv');
        run('cd {{deploy_path}}/current && vendor/bin/dep db:import --options=dumpcode:BackupProductionDkfz --no-interaction -vvv');
    }
});

// set shared dirs
set('shared_dirs', function () {
    return [
        'public/fileadmin',
        'public/uploads',
        'public/typo3temp/assets',
        'var/log',
        'var/lock',
        'var/transient',
        'var/goaccess',
        'var/phonebook',
        'var/xm_kesearch_remote',
    ];
});

// set writable dirs
set('writable_dirs', function () {
    return [
        get('web_path') . 'typo3conf',
        get('web_path') . 'typo3temp',
        get('web_path') . 'typo3temp/assets',
        get('web_path') . 'typo3temp/assets/images',
        get('web_path') . 'typo3temp/assets/_processed_',
        get('web_path') . 'uploads',
        get('web_path') . 'fileadmin',
        get('web_path') . '../var',
        get('web_path') . '../var/log',
        get('web_path') . '../var/lock',
        get('web_path') . '../var/transient',
        get('deploy_path') . '/shared/var/xm_kesearch_remote',
        get('web_path') . 'fileadmin/_processed_',
    ];
});
