<?php

namespace Deployer;

require_once(__DIR__ . '/vendor/blueways/deployer-recipes/autoload.php');

host('local')
    ->hostname('local')
    ->set('deploy_path', getcwd())
    ->set('public_urls', ['https://xm-dkfz-net.ddev.site']);
