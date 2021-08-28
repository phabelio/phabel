<?php

$os = ['self-hosted'];
$php = require 'versions.php';

$commit = \trim(\shell_exec("git log -1 --pretty=%H"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tag = \trim(\shell_exec("git describe --tags ".\escapeshellarg($commit)));

$doBuild = false;

$final = null;
$tail = \substr($branch, -3);
foreach ($php as $version) {
    $version = (string) $version;
    $final = $version[0].".".$version[1];
    if ($tail === "-$version") {
        break;
    }
}

$matrix = [
    'os' => $os,
    'php' => [$final],
    'shouldBuild' => [$doBuild ? 'yes' : 'no'],
    'shouldTag' => [$tag]
];
$matrix = \json_encode($matrix);

echo "::set-output name=matrix::$matrix".PHP_EOL;
