<?php

use Phabel\Cli\EventHandler;
use Phabel\Target\Php;
use Phabel\Traverser;

require 'vendor/autoload.php';
require 'functions.php';
$tail = isset($argv[1]) ? $argv[1] : '';
foreach (Php::VERSIONS as $version) {
    if ($tail === "-{$version}") {
        break;
    }
}
`rm -rf ../phabelConverted`;
$packages = (new Traverser(EventHandler::create()))->setPlugins([Php::class => ['target' => $version]])->setInput('vendor-bin/')->setOutput('../phabelConverted')->setCoverage('coverage/convertVendor.php')->run(\getenv('PHABEL_PARALLEL') ?: 1);
`rm -rf vendor-bin/`;
`mv ../phabelConverted/ vendor-bin/`;
if (!empty($packages)) {
    \chdir("vendor-bin/check");
    $cmd = "php \$(which composer) require --dev --ignore-platform-reqs ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}") . " ";
    }
    r($cmd);
}
