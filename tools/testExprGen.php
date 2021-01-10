<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\Target\Php;
use PhabelTest\TraverserTask;

use function Amp\Promise\all;
use function Amp\Promise\wait;

require_once 'vendor/autoload.php';

$fs = new Filesystem();

$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    $fs->remove("testsGenerated/Target$version");
    $fs->remove("testsGenerated/Target10$version");
    $packages []= $promise = TraverserTask::runAsync(
        [
            PhabelTestGenerator::class => ['target' => $version],
            TypeHintReplacer::class => ['union' => true, 'nullable' => true, 'return' => true, 'types' => [
                'callable', 'iterable', 'object', 'self', 'static',
                'int', 'float', 'array', 'string', 'bool',
                \PhabelTest\Target\TypeHintReplacerTest::class
            ]]
        ],
        'testsGenerated/Target',
        "testsGenerated/Target$version"
    );
    $promise->onResolve(function (?\Throwable $e, ?array $res) use ($version, &$packagesSecondary) {
        if ($e) {
            throw $e;
        }
        $packagesSecondary []= TraverserTask::runAsync(
            [
                PhabelTestGenerator::class => ['target' => 1000+$version]
            ],
            "testsGenerated/Target$version",
            "testsGenerated/Target10$version"
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
