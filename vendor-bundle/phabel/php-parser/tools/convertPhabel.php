<?php

namespace Phabel;

use Phabel\Target\Php;
use Phabel\Traverser;
if (!\file_exists('composer.json')) {
    echo "This script must be run from package root" . \PHP_EOL;
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
\passthru("git stash");
$branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
foreach ($target === 'all' ? Php::VERSIONS : [$target] as $realTarget) {
    if (!$dry) {
        \passthru("git branch -D phabel_tmp");
        \passthru("git branch phabel_tmp");
        \passthru("git checkout phabel_tmp");
    }
    foreach (['8.0', $realTarget] as $target) {
        $coverage = \getenv('PHABEL_COVERAGE') ?: '';
        if ($coverage) {
            $coverage .= "-{$target}";
        }
        $packages = [];
        foreach (['tools', 'src', 'bin', 'test', 'test_old', 'lib'] as $dir) {
            if (!\file_exists($dir)) {
                continue;
            }
            $packages += Traverser::run([Php::class => ['target' => $target]], $dir, $dir, $coverage);
        }
        $str = (string) $target;
        $packages["php"] = ">={$str[0]}.{$str[1]}";
        if (!empty($packages)) {
            $cmd = "composer require ";
            foreach ($packages as $package => $constraint) {
                $cmd .= \escapeshellarg("{$package}:{$constraint}") . " ";
            }
            \passthru($cmd);
        }
        \passthru("composer cs-fix");
        if (!$dry) {
            \passthru("git add -A");
            \passthru("git commit -m " . \escapeshellarg("phabel.io: transpile to {$target}"));
        }
    }
    if (!$dry) {
        \passthru("git push -f origin " . \escapeshellarg("phabel_tmp:{$branch}-{$target}"));
        \passthru("git checkout " . \escapeshellarg($branch));
        \passthru("git branch -D phabel_tmp");
    }
    \passthru("git reset --hard");
}
\passthru("git stash pop");
