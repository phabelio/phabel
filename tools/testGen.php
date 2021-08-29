<?php

use Composer\Util\Filesystem;
use Phabel\Cli\EventHandler;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Target\Php;
use Phabel\Traverser;

require_once 'vendor/autoload.php';


`rm -rf coverage`;

$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    echo "$version\n";
    `rm -rf "tests/Target$version"`;
    `rm -rf "tests/Target10$version"`;
    $packages += (new Traverser(EventHandler::create()))
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => $version]
        ])
        ->setInput('tests/Target')
        ->setOutput("tests/Target$version")
        ->setCoverage("test$version")
        ->run(\getenv('PHABEL_PARALLEL') ?: -1);

    (new Traverser(EventHandler::create()))
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => 1000+$version]
        ])
        ->setInput("tests/Target$version")
        ->setOutput("tests/Target10$version")
        ->setCoverage("test10$version")
        ->run(\getenv('PHABEL_PARALLEL') ?: -1);
}

unset($packages['php']);

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint")." ";
    }
    echo("Running $cmd...".PHP_EOL);
    \passthru($cmd);
}
