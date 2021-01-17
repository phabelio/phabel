<?php

use Composer\Util\Filesystem;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Target\Php;
use PhabelTest\TraverserTask;
use function Amp\Promise\all;
use function Amp\Promise\wait;

require_once 'vendor/autoload.php';
$fs = new Filesystem();
$fs->remove("coverage");
\mkdir("coverage");
$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    $fs->remove("tests/Target{$version}");
    $fs->remove("tests/Target10{$version}");
    $packages[] = $promise = TraverserTask::runAsync([PhabelTestGenerator::class => ['target' => $version]], 'tests/Target', "tests/Target{$version}", "test{$version}");
    $promise->onResolve(function ($e, $res) use ($version, &$packagesSecondary) {
        if (!($e instanceof \Exception || $e instanceof \Error || \is_null($e))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($e) must be of type ?Throwable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($e) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!(\is_array($res) || \is_null($res))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($res) must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($res) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if ($e) {
            throw $e;
        }
        $packagesSecondary[] = TraverserTask::runAsync([PhabelTestGenerator::class => ['target' => 1000 + $version]], "tests/Target{$version}", "tests/Target10{$version}", "test10{$version}");
    });
}
$packages = \array_merge(...wait(all($packages)));
wait(all($packagesSecondary));
if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}") . " ";
    }
    echo "Running {$cmd}..." . PHP_EOL;
    \passthru($cmd);
}
