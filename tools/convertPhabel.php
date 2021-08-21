<?php

use Phabel\Target\Php;
use Phabel\Traverser;

if (!\file_exists('composer.json')) {
    echo "This script must be run from package root" . PHP_EOL;
    die(1);
}
require 'vendor/autoload.php';
require 'ci/functions.php';
if ($argc < 2) {
    $help = <<<EOF
Usage: {$argv[0]} target [ dry ]

target - Target version
dry - 0 or 1, whether to dry-run conversion

EOF;
    echo $help;
    die(1);
}
$target = $argv[1];
$dry = (bool) ($argv[2] ?? '');
if (!\file_exists('../phabelConverted')) {
    \mkdir('../phabelConverted');
}
r("git stash");
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
foreach ($target === 'all' ? Php::VERSIONS : [$target] as $realTarget) {
    if (!$dry) {
        \passthru("git branch -D phabel_tmp");
        r("git branch phabel_tmp");
        r("git checkout phabel_tmp");
    }
    foreach ([Php::VERSIONS[\count(Php::VERSIONS)-1], $realTarget] as $target) {
        $coverage = \getenv('PHABEL_COVERAGE') ?: '';
        if ($coverage) {
            $coverage .= "-{$target}";
        }
        (new Traverser())
            ->setInput('.')
            ->setOutput('../phabelConverted')
            ->setPlugins([Php::class => ['target' => $target]])
            ->setCoverage($coverage)
            ->runAsync();
        foreach (['tools', 'src', 'bin'] as $dir) {
            if (!\file_exists("../phabelConverted/$dir")) {
                continue;
            }
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST,
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }

            \rmdir($dir);

            \rename("../phabelConverted/$dir", $dir);
        }
        $packages["php"] = ">=" . Php::unnormalizeVersion(Php::normalizeVersion($target));
        if (!empty($packages) && !$dry) {
            $cmd = "composer require ";
            foreach ($packages as $package => $constraint) {
                $cmd .= \escapeshellarg("{$package}:{$constraint}") . " ";
            }
            r($cmd);
        }
        r("composer cs-fix");
        if (!$dry) {
            r("git add -A");
            r("git commit -m " . \escapeshellarg("phabel.io: transpile to {$target}"));
        }
    }
    if (!$dry) {
        r("git push -f origin " . \escapeshellarg("phabel_tmp:{$branch}-{$target}"));
        r("git checkout " . \escapeshellarg($branch));
        r("git branch -D phabel_tmp");
    }
    r("git reset --hard");
}
\passthru("git stash pop");
