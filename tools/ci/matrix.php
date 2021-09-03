<?php

$os = ['self-hosted'];
$php = (require 'versions.php');
$commit = \trim(\shell_exec("git log -1 --pretty=%H"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$message = \trim(\shell_exec('git log --format=%B -n 1 ' . \escapeshellarg($commit)));
$tag = '';
if (\preg_match('/[(]tag ([^)]+)[)]/', $message, $matches)) {
    $tag = $matches[1];
}
$doBuild = true;
$final = null;
$tail = \Phabel\Target\Php80\Polyfill::substr($branch, -3);
foreach ($php as $version) {
    $version = (string) $version;
    $final = $version[0] . "." . $version[1];
    if ($tail === "-{$version}") {
        $doBuild = false;
        break;
    }
}
$matrix = ['os' => $os, 'php' => [$final], 'shouldBuild' => [$doBuild ? 'yes' : 'no'], 'shouldTag' => [$tag]];
$matrix = \json_encode($matrix);
echo "::set-output name=matrix::{$matrix}" . PHP_EOL;
