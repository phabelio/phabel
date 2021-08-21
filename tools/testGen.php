<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Target\Php;
use Phabel\Traverser;

require_once 'vendor/autoload.php';

$fs = new Filesystem();
$fs->remove("coverage");
\mkdir("coverage");

$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    $fs->remove("tests/Target$version");
    $fs->remove("tests/Target10$version");
    $packages += (new Traverser)
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => $version]
        ])
        ->setInput('tests/Target')
        ->setOutput("tests/Target$version")
        ->setCoverage("test$version")
        ->runAsync();
    $packagesSecondary += (new Traverser)
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => 1000+$version]
        ])
        ->setInput("tests/Target$version")
        ->setOutput("tests/Target10$version")
        ->setCoverage("test10$version")
        ->runAsync();
}

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint")." ";
    }
    echo("Running $cmd...".PHP_EOL);
    \passthru($cmd);
}
