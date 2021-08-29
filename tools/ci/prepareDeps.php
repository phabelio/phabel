<?php

use Phabel\Cli\EventHandler;
use Phabel\Target\Php;
use Phabel\Traverser;

require 'functions.php';
r("composer update --prefer-dist --ignore-platform-reqs");
if (\file_exists('vendor-bin')) {
    r("composer bin check update --prefer-dist --ignore-platform-reqs");
}
require 'vendor/autoload.php';
$branch = \getenv('BRANCH') ?: r("git rev-parse --abbrev-ref HEAD");
$version = 80;
if ($branch !== 'master') {
    $version = \substr($version, -2);
}
r("rm -rf ../phabelConvertedVendor");
$vendor = \file_exists('vendor-bin') ? 'vendor-bin/check/vendor' : 'vendor';
$packages = (new Traverser(EventHandler::create()))->setPlugins([Php::class => ['target' => $version]])->setInput($vendor)->setOutput('../phabelConvertedVendor')->setCoverage('coverage/convertVendor.php')->run(\getenv('PHABEL_PARALLEL') ?: 1);
r("cp -a {$vendor}/composer ../phabelConvertedVendor");
r("rm -rf {$vendor}");
\rename("../phabelConvertedVendor", $vendor);
$phpunit = \realpath("vendor/bin/phpunit");
\file_put_contents($phpunit, \str_replace('die(1);', '', \file_get_contents($phpunit)));
\chmod($phpunit, 0755);
