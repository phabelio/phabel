<?php

$os = ['self-hosted'];
$php = (require 'versions.php');
$commit = \trim(\shell_exec("git log -1 --pretty=%H"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$message = \trim(\shell_exec('git log --format=%B -n 1 ' . \escapeshellarg($commit)));
$tag = '';
if (\preg_match('/[(]tag ([^)]+)[)]/', $message, $matches)) {
    $tag = $matches[1];
} elseif (!\str_ends_with(\trim($message), 'notag)')) {
    $tag = \explode("\n", \trim(\shell_exec("git tag --sort=-creatordate")))[0];
    $tag = \preg_replace('/\\.\\d+$/', '', $tag);
    $tag = \explode('.', $tag);
    $tag[\count($tag) - 1]++;
    $tag = \implode('.', $tag);
    echo "Tagging {$tag}" . PHP_EOL;
}
$doBuild = true;
$final = null;
$tail = \substr($branch, -3);
foreach ($php as $version) {
    $version = (string) $version;
    $final = $version[0] . "." . $version[1];
    if ($tail === "-{$version}") {
        $doBuild = false;
        break;
    }
}
$matrix = ['os' => $os, 'php' => [$final], 'shouldBuild' => $doBuild ? $php : [''], 'shouldTag' => [$tag]];
$matrix = \json_encode($matrix);
echo "::set-output name=matrix::{$matrix}" . PHP_EOL;
