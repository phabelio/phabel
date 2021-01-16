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
    $packages += Traverser::run([Php::class => ['target' => $target]], $dir, "phabelConverted/$dir");
}

if (!empty($packages)) {
    $cmd = "composer require ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
    }
    \passthru($cmd);
}

$it = new \RecursiveDirectoryIterator("phabelConverted", \RecursiveDirectoryIterator::SKIP_DOTS);
$ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
/** @var \SplFileInfo $file */
foreach ($ri as $file) {
    if ($file->isFile()) {
        \copy($file->getRealPath(), \str_replace("phabelConverted/", "", $file->getRealPath()));
    }
}

$message = \trim(\shell_exec("git log -1 --pretty=%B"));
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));

\passthru("git add -A");
\passthru("git commit -m ".\escapeshellarg($message));

$hash = \trim(\shell_exec("git log -1 --pretty=%H"));
\passthru("git push -f origin ".\escapeshellarg("$hash:refs/heads/{$branch}-{$target}"));
\passthru("git reset HEAD~1");
\passthru("git reset --hard");
