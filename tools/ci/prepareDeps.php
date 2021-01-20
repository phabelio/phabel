<?php

use Phabel\Target\Php;
use PhabelTest\TraverserTask;

$php = require 'versions.php';

$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tail = \substr($branch, -3);
foreach ($php as $version) {
    if ($tail === "-$version") {
        break;
    }
}

\passthru("composer install --prefer-dist --ignore-platform-reqs");

require 'vendor/autoload.php';

$packages = TraverserTask::runAsync([Php::class => ['target' => $version]], 'vendor', 'vendor', 'coverage/convertVendor');

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    \passthru($cmd);
}
