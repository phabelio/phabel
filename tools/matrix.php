<?php

$os = ['ubuntu-latest', 'windows-latest', 'macos-latest'];
$php = [56, 70, 71, 72, 73, 80];

$commit = \trim(\shell_exec("git log -1 --pretty=%H"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$tag = \trim(\shell_exec("git describe --tags ".\escapeshellarg($commit)));

$doBuild = false;

$final = [];
$ok = false;
$tail = \substr($branch, -3);
foreach ($php as $version) {
    if ($tail === "-$version") {
        $ok = true;
    }
    if ($ok) {
        $version = (string) $version;
        $final []= $version[0].".".$version[1];
    }
}
if (!$final) {
    $final = ["8.0"];
    $doBuild = true;
}

$matrix = [
    'os' => $os,
    'php' => $final,
    'shouldBuild' => [$doBuild ? 'yes' : 'no'],
    'shouldTag' => [$tag]
];
$matrix = \json_encode($matrix);

echo "::set-output name=matrix::$matrix".PHP_EOL;
