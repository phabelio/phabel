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

$packages = Traverser::run([Php::class => ['target' => $version]], '.', '../phabelConverted', 'coverage/convertVendor.php');

`cp -a ../phabelConverted/vendor .`;

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    r($cmd);
}
