<?php

namespace Deployer;

use SourceBroker\DeployerExtendedTypo3\Utility\ConsoleUtility;

require_once(__DIR__ . '/vendor/xima/xima-deployer-extended-typo3/autoload.php');

set('repository', 'git@git.xima.de:typo3/dkfz/dkfz-intranet-typo3.git');

function defineTestHost($branchName, $stage): void
{
    host('dev-t3-debian11-01-' . strtolower($branchName))
        ->setHostname('10.19.8.4')
        ->setRemoteUser('xima')
        ->set('labels', ['stage' => $stage])
        ->set('branch', $branchName)
        ->set('bin/php', '/usr/bin/php8.1')
        ->set('public_urls', ['https://' . strtolower($branchName) . '.dev.dkfz-intranet-typo3.xima.dev'])
        ->set('deploy_path', '/var/www/html/dkfz-intranet-typo3_dev_' . strtolower($branchName));
}

for ($i = 1; $i <= 999; $i++) {
    $branchName = 'DKFZ-' . $i;
    defineTestHost($branchName, 'feature');

    $branchName = 'DIS-' . $i;
    defineTestHost($branchName, 'feature');
}

defineTestHost('master', 'staging');
defineTestHost('development', 'staging');

host('staging-dkfz')
    ->setHostname('intracmsstage')
    ->setRemoteUser('xima')
    ->set('labels', ['stage' => 'staging-dkfz'])
    ->set('branch', 'development')
    ->set('bin/php', '/usr/bin/php8.1')
    ->set('repository', 'git@git.dkfz.de:dkfz/dkfz-t3-intranet.git')
    ->set('public_urls', ['https://intranetstage.dkfz.de'])
    ->set('deploy_path', '/var/www/html/intracmsstage.dkfz-heidelberg.de');

host('production-dkfz')
    ->setHostname('intracmsprod')
    ->set('labels', ['stage' => 'production-dkfz'])
    ->setRemoteUser('xima')
    ->set('branch', 'master')
    ->set('repository', 'git@git.dkfz.de:dkfz/dkfz-t3-intranet.git')
    ->set('public_urls', ['https://intranet.dkfz.de'])
    ->set('http_user', 'www-data')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_recursive', false)
    ->set('writable_chmod_mode', '2770')
    ->set('deploy_path', '/var/www/html/intracmsprod.dkfz-heidelberg.de')
    ->set('fetch_method', 'curl')
    ->set('media_rsync_flags', '-rz --perms');

// Upload of dist files
after('deploy:update_code', 'deploy:upload-dist');
task('deploy:upload-dist', function () {
    upload(
        'packages/xm_dkfz_net_site/Resources/Public/Css/dist/',
        '{{release_path}}/packages/xm_dkfz_net_site/Resources/Public/Css/dist/',
    );
    upload(
        'packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/',
        '{{release_path}}/packages/xm_dkfz_net_site/Resources/Public/JavaScript/dist/',
    );
    run('find {{release_path}}/packages/xm_dkfz_net_site/Resources/Public/ -type d -exec chmod 2770 {} \;');
    run('find {{release_path}}/packages/xm_dkfz_net_site/Resources/Public/ -type f -exec chmod 0640 {} \;');
});

// Cache warmup
task('typo3cms:cache:warmup', function () {
    $activePath = get('deploy_path') . '/' . (test('[ -L {{deploy_path}}/release ]') ? 'release' : 'current');
    run('cd ' . $activePath . ' && {{bin/php}} {{bin/typo3cms}} cache:warmup');
    run('cd ' . $activePath . ' && {{bin/php}} {{bin/typo3cms}} warming:cachewarmup -p 1,4,5,6,7,8');
});
task('typo3cms:cache:warmup-live', function () {
    $activePath = get('deploy_path') . '/' . (test('[ -L {{deploy_path}}/release ]') ? 'release' : 'current');
    run('cd ' . $activePath . ' && {{bin/php}} {{bin/typo3cms}} cache:warmup');
    run('cd ' . $activePath . ' && {{bin/php}} {{bin/typo3cms}} warming:cachewarmup --sites=1');
});
before('buffer:start', 'typo3cms:cache:warmup');
after('buffer:stop', 'typo3cms:cache:warmup-live');

// Reset xima intern staging in pipeline
option(
    'DKFZ_ACCESS_TOKEN',
    null,
    \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
    'Gitlab token of DKFZ production repo.'
);
task('reset:from_production_artifact', function () {
    if (get('is_argument_host_the_same_as_local_host')) {
        $activeDir = get('deploy_path') . (testLocally('[ -e {{deploy_path}}/release ]') ? '/release' : '/current');
        $activeDir = testLocally('[ -e ' . $activeDir . ' ]') ? $activeDir : get('deploy_path');
        runLocally('cd ' . $activeDir . ' && curl --location --output artifacts.zip --header "PRIVATE-TOKEN: {{DKFZ_ACCESS_TOKEN}}" "https://git.dkfz.de/api/v4/projects/69/jobs/artifacts/master/download?job=backup-production-dkfz"');
        runLocally('cd ' . $activeDir . ' && vendor/bin/dep db:rmdump {{argument_host}} --options=dumpcode:BackupProductionDkfz --no-interaction');
        runLocally('cd ' . $activeDir . ' && unzip -o artifacts.zip');
        runLocally('cd ' . $activeDir . ' && mv -n .dep/database/dumps/*dumpcode=BackupProductionDkfz* {{db_storage_path_local}}/');
        runLocally('cd ' . $activeDir . ' && vendor/bin/dep db:decompress {{argument_host}} --options=dumpcode:BackupProductionDkfz --no-interaction');
        runLocally('cd ' . $activeDir . ' && vendor/bin/dep db:import {{argument_host}} --options=dumpcode:BackupProductionDkfz --no-interaction');
        runLocally('cd ' . $activeDir . ' && vendor/bin/dep db:rmdump {{argument_host}} --options=dumpcode:BackupProductionDkfz --no-interaction');
        runLocally('cd ' . $activeDir . ' && rm -f artifacts.zip');
        runLocally('cd ' . $activeDir . ' && {{local/bin/php}} {{bin/typo3cms}} cache:flush');
        runLocally('cd ' . $activeDir . ' && {{local/bin/php}} {{bin/typo3cms}} cache:warmup');
        runLocally('cd ' . $activeDir . ' && {{local/bin/php}} {{bin/typo3cms}} warming:cachewarmup --sites=1');
    } else {
        $verbosity = (new ConsoleUtility())->getVerbosityAsParameter();
        run('cd {{release_or_current_path}} && {{bin/php}} {{bin/deployer}} reset:from_production_artifact {{argument_host}} -o DKFZ_ACCESS_TOKEN="{{DKFZ_ACCESS_TOKEN}}" ' . $verbosity);
    }
});

// set shared dirs
set('shared_dirs', function () {
    return [
        'public/fileadmin',
        'public/uploads',
        'public/typo3temp/assets',
        'var/log',
        'var/transient',
        'var/goaccess',
        'var/phonebook',
        'var/xm_kesearch_remote',
    ];
});
