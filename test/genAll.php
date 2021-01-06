<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Plugin\TestNamespaceReplacer;
use Phabel\Target\Php;
use Phabel\Traverser;

if (PHP_MAJOR_VERSION !== 8) {
    echo("The test generator can only be run on PHP 8.0+".PHP_EOL);
    die(1);
}

require_once __DIR__.'/../vendor/autoload.php';
/*
echo("Running expression generator...".PHP_EOL);
require_once __DIR__.'/exprGen.php';

echo("Running typehint generator...".PHP_EOL);
require_once __DIR__.'/typeHintGen.php';
*/

$fs = new Filesystem();

$packages = [];
foreach (Php::VERSIONS as $version) {
    echo("Converting target $version...".PHP_EOL);
    $fs->remove(__DIR__."/Target$version");
    $packages += Traverser::run(
        [
            PhabelTestGenerator::class => ['target' => $version]
        ],
        __DIR__.'/Target',
        __DIR__."/Target$version"
    );
}

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint");
    }
    echo("Running $cmd...".PHP_EOL);
    \passthru($cmd);
}