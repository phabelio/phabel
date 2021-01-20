<?php

use Phabel\Target\Php;
use PhabelTest\TraverserTask;

use function Amp\Promise\wait;

require 'vendor/autoload.php';
require 'functions.php';

$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tail = \substr($branch, -3);
foreach (Php::VERSIONS as $version) {
    if ($tail === "-$version") {
        break;
    }
}

$packages = wait(TraverserTask::runAsync([Php::class => ['target' => $version]], 'vendor', 'vendor', 'coverage/convertVendor'));

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    r($cmd);
}
