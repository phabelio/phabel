<?php

use Phabel\Target\Php;
use Phabel\Traverser;

require 'vendor/autoload.php';
require 'functions.php';

$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tail = \substr($branch, -3);
foreach (Php::VERSIONS as $version) {
    if ($tail === "-$version") {
        break;
    }
}

$packages = Traverser::run([Php::class => ['target' => $version]], '.', '../phabelConverted', 'coverage/convertVendor.php');


$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('vendor', RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST,
);

foreach ($files as $fileinfo) {
    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
    $todo($fileinfo->getRealPath());
}

\rmdir('vendor');
\rename('../phabelConverted/vendor', 'vendor');

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    r($cmd);
}
