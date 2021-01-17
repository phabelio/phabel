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

\passthru("git stash");

foreach ($target === 'all' ? Php::VERSIONS : [$target] as $target) {
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

    if (!empty($packages)) {
        $cmd = "composer require ";
        foreach ($packages as $package => $constraint) {
            $cmd .= \escapeshellarg("{$package}:{$constraint}")." ";
        }
        \passthru($cmd);
    }

    \passthru("composer cs-fix");

    if (!$dry) {
        $branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));

        \passthru("git branch -D phabel_tmp");
        \passthru("git branch phabel_tmp");
        \passthru("git checkout phabel_tmp");

        \passthru("git add -A");
        \passthru("git commit -m ".\escapeshellarg("phabel.io: transpile to $target"));

        \passthru("git push -f origin ".\escapeshellarg("phabel_tmp:{$branch}-{$target}"));
        if ($tag = getenv('shouldTag')) {
            $tag .= ".$target";
            $commit = \trim(\shell_exec("git log -1 --pretty=%H"));
            passthru("git tag ".escapeshellarg("{$branch}-{$target}")." ".escapeshellarg($commit));
            passthru("git push origin ".escapeshellarg($tag));
        }
        \passthru("git checkout ".\escapeshellarg($branch));
        \passthru("git branch -D phabel_tmp");
    }
    \passthru("git reset --hard");
}

\passthru("git stash pop");
