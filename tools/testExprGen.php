<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\Target\Php;
use PhabelTest\TraverserTask;
use SebastianBergmann\CodeCoverage\Report\PHP as ReportPHP;

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
        "testsGenerated/Target$version",
        getenv('CI') ? false : true
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
            "testsGenerated/Target10$version",
            getenv('CI') ? false : true
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

if ($coverage = TraverserTask::getCoverage()) {
    (new ReportPhp)->process($coverage, $argv[1] ?? 'coverage/transpilerExpr.php');
}

$current = (int) (PHP_MAJOR_VERSION.PHP_MINOR_VERSION);
foreach (glob("testsGenerated/*/*.php") as $i => $test) {
    $version = (int) substr(basename(dirname($test)), 6);
    $version = $version ?: 80;
    if ($version > $current) {
        continue;
    }
    
    passthru(PHP_BINARY." vendor/bin/phpunit -c phpunit-expr.xml $test --coverage-php=coverage/transpilerExpr$i.php");
}
