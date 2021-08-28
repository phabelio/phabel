<?php

$os = ['self-hosted'];
$php = (require 'versions.php');
$commit = \trim(\shell_exec("git log -1 --pretty=%H"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tag = \trim(\shell_exec("git describe --tags " . \escapeshellarg($commit)));
$doBuild = false;
$final = [];
$ok = false;
$tail = \substr($branch, -3);
foreach ($php as $version) {
    if ($tail === "-{$version}") {
        $ok = true;
        $version = (string) $version;
        $final[] = $version[0] . "." . $version[1];
        $versionEnd = (string) \end($php);
        if ($version !== $versionEnd) {
            $final[] = $versionEnd[0] . "." . $versionEnd[1];
        }
        break;
    }
}
if (!$final) {
    $versionEnd = (string) \end($php);
    $final[] = $versionEnd[0] . "." . $versionEnd[1];
    $doBuild = true;
}
$matrix = ['os' => $os, 'php' => $final, 'shouldBuild' => [$doBuild ? 'yes' : 'no'], 'shouldTag' => [$tag]];
$matrix = \json_encode($matrix);
echo "::set-output name=matrix::{$matrix}" . PHP_EOL;
