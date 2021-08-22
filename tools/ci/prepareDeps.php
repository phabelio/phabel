<?php

use Phabel\Cli\EventHandler;
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

$packages = (new Traverser(EventHandler::create()))
    ->setPlugins([Php::class => ['target' => $version]])
    ->setInput('.')
    ->setOutput('../phabelConverted')
    ->setCoverage('coverage/convertVendor.php')
    ->runAsync();

`cp -a ../phabelConverted/vendor .`;
`cp -a ../phabelConverted/vendor-bin .`;

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    r($cmd);
}
