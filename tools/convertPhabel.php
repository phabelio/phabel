<?php

use Phabel\Target\Php;
use Phabel\Traverser;

if (!\file_exists('composer.json')) {
    echo "This script must be run from package root".PHP_EOL;
    die(1);
}
require 'vendor/autoload.php';

if ($argc !== 2) {
    $help = <<<EOF
Usage: {$argv[0]} target

target - Target version

EOF;
    echo $help;
    die(1);
}
$target = $argv[1];

if (!\file_exists('phabelConverted')) {
    \mkdir('phabelConverted');
}

$packages = [];
foreach (['tools', 'src', 'bin'] as $dir) {
    if (!\file_exists($dir)) {
        continue;
    }
    $packages += Traverser::run([Php::class => ['target' => $target]], $dir, $dir);
}

if (!empty($packages)) {
    $cmd = "composer require ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    \passthru($cmd);
}

$message = \trim(\shell_exec("git log -1 --pretty=%B"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
$oldHash = \trim(\shell_exec("git log -1 --pretty=%H"));

\passthru("git add -A");
\passthru("git commit -m ".\escapeshellarg($message));

$hash = \trim(\shell_exec("git log -1 --pretty=%H"));
\passthru("git push -f origin ".\escapeshellarg("$hash:refs/heads/{$branch}-{$target}"));
\passthru("git reset $oldHash");
\passthru("git reset --hard");
