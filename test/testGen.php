<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Target\Php;
use PhabelTest\TraverserTask;

use function Amp\Promise\all;
use function Amp\Promise\wait;

require_once __DIR__.'/../vendor/autoload.php';
/*
echo("Running expression generator...".PHP_EOL);
require_once __DIR__.'/exprGen.php';

echo("Running typehint generator...".PHP_EOL);
require_once __DIR__.'/typeHintGen.php';
*/

$fs = new Filesystem();

$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    $fs->remove(__DIR__."/Target$version");
    $fs->remove(__DIR__."/Target10$version");
    $packages []= $promise = TraverserTask::runAsync(
        [
            PhabelTestGenerator::class => ['target' => $version]
        ],
        __DIR__.'/Target',
        __DIR__."/Target$version"
    );
    $promise->onResolve(function (?\Throwable $e, ?array $res) use ($version, &$packagesSecondary) {
        if ($e) {
            throw $e;
        }
        $packagesSecondary []= TraverserTask::runAsync(
            [
                PhabelTestGenerator::class => ['target' => 1000+$version]
            ],
            __DIR__."/Target$version",
            __DIR__."/Target10$version"
        );
    });
}
$packages = \array_merge(...wait(all($packages)));
wait(all($packagesSecondary));


if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint")." ";
    }
    echo("Running $cmd...".PHP_EOL);
    \passthru($cmd);
}
