<?php

use Phabel\Target\Php;
use Phabel\Traverser;

if (!\file_exists('composer.json')) {
    echo "This script must be run from package root".PHP_EOL;
    die(1);
}
require 'vendor/autoload.php';

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

if (!\file_exists('phabelConverted')) {
    \mkdir('phabelConverted');
}

r("git stash");
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));

foreach ($target === 'all' ? Php::VERSIONS : [$target] as $realTarget) {
    if (!$dry) {
        \passthru("git branch -D phabel_tmp");
        r("git branch phabel_tmp");
        r("git checkout phabel_tmp");
    }
    foreach (['8.0', $realTarget] as $target) {
        $coverage = \getenv('PHABEL_COVERAGE') ?: '';
        if ($coverage) {
            $coverage .= "-$target";
        }

        $packages = [];
        foreach (['tools', 'src', 'bin'] as $dir) {
            if (!\file_exists($dir)) {
                continue;
            }
            $packages += Traverser::run([Php::class => ['target' => $target]], $dir, $dir, $coverage);
        }

        $str = (string) $target;
        $packages["php"] = ">=${str[0]}.${str[1]}";
        if (!empty($packages)) {
            $cmd = "composer require ";
            foreach ($packages as $package => $constraint) {
                $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
            }
            r($cmd);
        }

        r("composer cs-fix");

        if (!$dry) {
            r("git add -A");
            r("git commit -m ".\escapeshellarg("phabel.io: transpile to $target"));
        }
    }
    if (!$dry) {
        r("git push -f origin ".\escapeshellarg("phabel_tmp:{$branch}-{$target}"));

        r("git checkout ".\escapeshellarg($branch));
        r("git branch -D phabel_tmp");
    }
    r("git reset --hard");
}

r("git stash pop");