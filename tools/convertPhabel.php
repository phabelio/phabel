<?php

use Phabel\Cli\EventHandler;
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
$tag = \getenv('shouldTag') ?: null;

$home = \realpath(__DIR__.'/../');
r("rm -rf ../phabelConvertedInput");
\mkdir('../phabelConvertedInput');

if (!$dry) {
    r("rm -rf ../phabelConvertedRepo");
    r("cp -a $home ../phabelConvertedRepo");
    r("rm -rf ../phabelConvertedRepo/vendor");
}

function commit(string $message)
{
    r("cp -a ../phabelConvertedOutput/* ../phabelConvertedRepo");
    \chdir("../phabelConvertedRepo/");
    r("git add -A");
    r("git commit -m " . \escapeshellarg($message));
}

$last = Php::VERSIONS;
$last = \end($last);

foreach ($target === 'all' ? Php::VERSIONS : [$target] as $realTarget) {
    $realTarget = Php::normalizeVersion($realTarget);
    if (!$dry) {
        \chdir("../phabelConvertedRepo");
        \passthru("git branch -D phabel_tmp");
        r("git branch phabel_tmp");
        r("git checkout phabel_tmp");
        r("rm -rf ../phabelConvertedRepo/*");
    }
    r("rm -rf ../phabelConvertedOutput");
    \mkdir('../phabelConvertedOutput');

    $coverage = \getenv('PHABEL_COVERAGE') ?: '';
    if ($coverage) {
        $coverage .= "-{$realTarget}";
    }
    $packages = (new Traverser(EventHandler::create()))
        ->setInput('../phabelConvertedOutput')
        ->setOutput('../phabelConvertedOutput')
        ->setPlugins([Php::class => ['target' => $realTarget]])
        ->setCoverage($coverage)
        ->run((int) (\getenv('PHABEL_PARALLEL') ?: 1));

    \chdir($home);
    r("cp -a * .php-cs-fixer.dist.php ../phabelConvertedInput");
    \chdir("../phabelConvertedInput");
    r("rm -rf vendor-bin/*/vendor");
    if (!empty($packages)) {
        unset($packages['phabel/phabel']);
        $json = \json_decode(\file_get_contents('composer.json'), true);
        foreach ($packages as $package => $constraint) {
            if ($package === 'php') {
                continue;
            }
            if (isset($json['require-dev'][$package])) {
                unset($json['require-dev'][$package]);
            }
            $json['require'][$package] = $constraint;
        }
        \file_put_contents('composer.json', \json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }
    r("composer update --no-dev");
    r("rm -rf tests testsGenerated");

    foreach ([Php::VERSIONS[\count(Php::VERSIONS)-1], $realTarget] as $k => $target) {
        if ($k === 0) {
            $input = "../phabelConvertedInput";
            $output = "../phabelConvertedOutput";
        } else {
            $input = $output = "../phabelConvertedOutput";
        }
        \chdir($input);

        $coverage = \getenv('PHABEL_COVERAGE') ?: '';
        if ($coverage) {
            $coverage .= "-{$target}";
        }
        (new Traverser(EventHandler::create()))
            ->setInput($input)
            ->setOutput($output)
            ->setPlugins([Php::class => ['target' => $target]])
            ->setCoverage($coverage)
            ->run((int) (\getenv('PHABEL_PARALLEL') ?: 1));

        \chdir($output);

        if (!$dry) {
            r("$home/vendor/bin/php-cs-fixer fix");
            commit("phabel.io: transpile to {$target}");
        }
    }
    \chdir($home);
    r("cp -a testsGenerated tests ../phabelConvertedOutput");
    \copy("tools/ci/matrix.php", "../phabelConvertedOutput/tools/ci/matrix.php");
    \chdir("../phabelConvertedOutput");
    r("$home/vendor/bin/php-scoper add-prefix -c $home/scoper.inc.php");
    r("cp -a vendor/symfony/polyfill* build/vendor/symfony");
    r("rm -rf vendor");
    r("cp -a build/. .");
    r("rm -rf build vendor/composer vendor/autoload.php vendor/scoper-autoload.php vendor/bin");

    // Patch symfony/string
    \file_put_contents(
        'vendor/symfony/string/AbstractString.php',
        \str_replace('\\PREG_UNMATCHED_AS_NULL', "0", \file_get_contents('vendor/symfony/string/AbstractString.php'))
    );
    $replace = 'array_walk_recursive($match, function (&$v){return $v === "" ? null : $v;})';
    \file_put_contents(
        'vendor/symfony/string/AbstractUnicodeString.php',
        \str_replace(['return $matches;', '\\PREG_UNMATCHED_AS_NULL'], ["$replace; return \$matches;", "0"], \file_get_contents('vendor/symfony/string/AbstractUnicodeString.php'))
    );
    \file_put_contents(
        'vendor/symfony/string/ByteString.php',
        \str_replace(['return $matches;', '\\PREG_UNMATCHED_AS_NULL'], ["$replace; return \$matches;", "0"], \file_get_contents('vendor/symfony/string/ByteString.php'))
    );

    \file_put_contents(
        'src/Composer/Plugin.php',
        \str_replace('PhabelVendor\\Symfony', 'Symfony', \file_get_contents('src/Composer/Plugin.php'))
    );
    \file_put_contents(
        'src/Composer/Transformer.php',
        \str_replace('PhabelVendor\\Symfony', 'Symfony', \file_get_contents('src/Composer/Transformer.php'))
    );

    \rename("vendor", "vendor-bundle");
    r("find src -type f -exec sed 's/\\\\PhabelVendor\\\\self/self/g' -i {} +");

    \file_put_contents('vendor-bundle/autoload.php', <<<PHP
        <?php
        if (file_exists(__DIR__.'/../vendor/autoload.php')) return require __DIR__.'/../vendor/autoload.php';
        return require __DIR__.'/../../../autoload.php';
    PHP);

    $lock = \json_decode(\file_get_contents('composer.lock'), true);
    $json = \json_decode(\file_get_contents('composer.json'), true);

    $json['require'] = [
        'php' => $packages['php'],
        'ext-json' => $json['require']['ext-json'],
        'composer-plugin-api' => $json['require']['composer-plugin-api'],
    ];
    $json['require-dev'] = [
        'bamarni/composer-bin-plugin' => $json['require-dev']['bamarni/composer-bin-plugin'],
        'amphp/file' => $json['require-dev']['amphp/file']
    ];

    if ($realTarget === $last) {
        $json['require']['php'] = '>='.Php::unnormalizeVersion($last);
    }

    foreach ($lock['packages'] as $package) {
        $name = $package['name'];

        if ($name === 'phabel/phabel') {
            continue;
        }

        $json['require'] += \array_filter($package['require'], fn ($s) => \str_starts_with($s, 'ext-'), ARRAY_FILTER_USE_KEY);

        foreach (['psr-4', 'psr-0'] as $type) {
            foreach ($package['autoload'][$type] ?? [] as $namespace => $path) {
                $namespace = \str_starts_with($namespace, 'Symfony\\Polyfill') ? $namespace : "PhabelVendor\\$namespace";
                $paths = \is_string($path) ? [$path] : $path;
                $paths = \array_map(fn ($path) => "vendor-bundle/$name/$path", $paths);
                $json['autoload'][$type][$namespace] = $paths;
            }
        }

        foreach (['classmap', 'files'] as $type) {
            $json['autoload'][$type] = \array_merge(
                $json['autoload'][$type] ?? [],
                \array_map(
                    fn ($path) => "vendor-bundle/$name/$path",
                    $package['autoload'][$type] ?? []
                )
            );
        }
    }
    /*$it = new \RecursiveDirectoryIterator('src', \RecursiveDirectoryIterator::SKIP_DOTS);
    $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);

    $allUses = [];
    foreach ($ri as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        if (\preg_match_all('/^use (\S+?)(?: as \S+)?;$/m', \file_get_contents($file->getRealPath()), $uses)) {
            foreach ($uses[1] as $class) {
                $allUses []= "\\$class::class";
            }
        }
    }

    $allUses = \implode(', ', $allUses);
    $allUses = "array_map('class_exists', [$allUses]);";

    \file_put_contents(
        'src/guard.php',
        \file_get_contents('src/guard.php').$allUses
    );*/

    foreach ($json['autoload']['files'] ?? [] as $file) {
        $path = \dirname($file);
        $file = \basename($file);
        \rename("$path/$file", "$path/guard.$file");
        \file_put_contents("$path/$file", "<?php require_once __DIR__.DIRECTORY_SEPARATOR.'guard.$file';");
    }

    $json['autoload-dev'] = ['psr-4' => ['PhabelTest\\' => 'tests/']];

    \file_put_contents('composer.json', \json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    if (!$dry) {
        \chdir("../phabelConvertedRepo");
        if ($tag) {
            commit("phabel.io: add dependencies (tag $tag.$target)");
        } else {
            commit("phabel.io: add dependencies");
        }
        r("git push -f origin " . \escapeshellarg("phabel_tmp:{$branch}-{$target}"));
        r("git checkout " . \escapeshellarg($branch));
        r("git branch -D phabel_tmp");
    }
}
