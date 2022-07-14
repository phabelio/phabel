<?php

use Phabel\Cli\EventHandler;
use Phabel\Target\Php;
use Phabel\Traverser;

require 'functions.php';
r("composer update --prefer-dist --ignore-platform-reqs");
r("composer bin check update --prefer-dist --ignore-platform-reqs");
require 'vendor/autoload.php';
$branch = \getenv('BRANCH') ?: r("git rev-parse --abbrev-ref HEAD");
$version = 80;
if ($branch !== 'master') {
    $version = \substr($version, -2);
}
r("rm -rf ../phabelConvertedVendor");
$packages = (new Traverser(EventHandler::create()))->setPlugins([Php::class => ['target' => $version]])->setInput('vendor-bin/check/vendor')->setOutput('../phabelConvertedVendor')->setCoverage('coverage/convertVendor.php')->run(\getenv('PHABEL_PARALLEL') ?: 1);
r("cp -a vendor-bin/check/vendor/composer ../phabelConvertedVendor");
r("rm -rf vendor-bin/check/vendor");
\rename("../phabelConvertedVendor", "vendor-bin/check/vendor");
foreach (['vendor/bin/phpunit', 'vendor-bin/check/vendor/phpunit/phpunit/phpunit'] as $phpunit) {
    $phpunit = \realpath($phpunit);
    \file_put_contents($phpunit, \str_replace('die(1);', '', \file_get_contents($phpunit)));
    \chmod($phpunit, 0755);
}
