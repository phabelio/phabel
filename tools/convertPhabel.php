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
$branch = 'master';

$home = \realpath(__DIR__.'/../');
r("rm -rf /tmp/phabelConvertedInput");
\mkdir('/tmp/phabelConvertedInput');

if (!$dry) {
    r("rm -rf /tmp/phabelConvertedRepo");
    r("cp -a $home /tmp/phabelConvertedRepo");
    r("rm -rf /tmp/phabelConvertedRepo/vendor");
}

\chdir($home);
r("composer install");
r("cp -a * .php-cs-fixer.dist.php /tmp/phabelConvertedInput");
\chdir("/tmp/phabelConvertedInput");
r("rm -rf vendor-bin/*/vendor");
r("composer update --no-dev");

function commit(string $message)
{
    r("cp -a /tmp/phabelConvertedOutput/* /tmp/phabelConvertedRepo");
    \chdir("/tmp/phabelConvertedRepo/");
    r("git add -A");
    r("git commit -m " . \escapeshellarg($message));
}

foreach ($target === 'all' ? Php::VERSIONS : [$target] as $realTarget) {
    if (!$dry) {
        \chdir("/tmp/phabelConvertedRepo");
        \passthru("git branch -D phabel_tmp");
        r("git branch phabel_tmp");
        r("git checkout phabel_tmp");
        r("rm -rf /tmp/phabelConvertedRepo/*");
    }
    r("rm -rf /tmp/phabelConvertedOutput");
    \mkdir('/tmp/phabelConvertedOutput');

    $packages = [];
    foreach ([Php::VERSIONS[\count(Php::VERSIONS)-1], $realTarget] as $k => $target) {
        if ($k === 0) {
            $input = "/tmp/phabelConvertedInput";
            $output = "/tmp/phabelConvertedOutput";
        } else {
            $input = $output = "/tmp/phabelConvertedOutput";
        }
        \chdir($input);

        $coverage = \getenv('PHABEL_COVERAGE') ?: '';
        if ($coverage) {
            $coverage .= "-{$target}";
        }
        $packages += (new Traverser())
            ->setInput($input)
            ->setOutput($output)
            ->setPlugins([Php::class => ['target' => $target]])
            ->setCoverage($coverage)
            ->run((int) (getenv('PHABEL_PARALLEL') ?: 1));

        \chdir($output);
        r("$home/vendor/bin/php-cs-fixer fix");

        if (!$dry) {
            commit("phabel.io: transpile to {$target}");
        }
    }
    \chdir("/tmp/phabelConvertedOutput");
    r("$home/vendor/bin/php-scoper add-prefix -c $home/scoper.inc.php");
    r("rm -rf vendor");
    r("cp -a build/. .");
    r("rm -rf build vendor/composer vendor/autoload.php vendor/scoper-autoload.php vendor/bin");
    \rename("vendor", "vendor-bundle");

    \file_put_contents('vendor-bundle/autoload.php', <<<PHP
        <?php
        if (file_exists(__DIR__.'/../vendor/autoload.php')) return require __DIR__.'/../vendor/autoload.php';
        return require __DIR__.'/../../../autoload.php';
    PHP);

    $packages["php"] = ">=" . Php::unnormalizeVersion(Php::normalizeVersion($target));

    $lock = \json_decode(\file_get_contents('composer.lock'), true);
    $json = \json_decode(\file_get_contents('composer.json'), true);

    $json['require'] = $packages;
    foreach ($lock['packages'] as $package) {
        $name = $package['name'];

        if ($name === 'phabel/phabel') {
            continue;
        }
        foreach (['psr-4', 'psr-0'] as $type) {
            foreach ($package['autoload'][$type] ?? [] as $namespace => $path) {
                $namespace = "Phabel\\$namespace";
                $paths = \is_string($path) ? [$path] : $path;
                $paths = \array_map(fn ($path) => "vendor-bundle/$name/$path", $paths);
                $json['autoload'][$type][$namespace] = $paths;
            }
        }
        $json['autoload']['files'] = \array_merge(
            $json['autoload']['files'] ?? [],
            \array_map(
                fn ($path) => "vendor-bundle/$name/$path",
                $package['autoload']['files'] ?? []
            )
        );
    }

    \file_put_contents('composer.json', \json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    if (!$dry) {
        commit("phabel.io: add dependencies");
        \chdir("/tmp/phabelConvertedRepo");
        r("git push -f origin " . \escapeshellarg("phabel_tmp:{$branch}-{$target}"));
        r("git checkout " . \escapeshellarg($branch));
        r("git branch -D phabel_tmp");
    }
}
