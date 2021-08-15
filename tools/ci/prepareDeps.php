<?php

use Phabel\Target\Php;
use Phabel\Traverser;

require 'vendor/autoload.php';
require 'functions.php';

$tail = $argv[1];
foreach (Php::VERSIONS as $version) {
    if ($tail === "-$version") {
        break;
    }
}

$packages = (new Traverser)
    ->setPlugins([Php::class => ['target' => $version]])
    ->setInput('.')
    ->setOutput('../phabelConverted')
    ->setCoverage('coverage/convertVendor.php')
    ->run();

`cp -a ../phabelConverted/vendor .`;

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    r($cmd);
}
