<?php

use Amp\Parallel\Worker\DefaultPool;
use Phabel\Cli\EventHandler;
use Phabel\Plugin\PhabelTestGenerator;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\Target\Php;
use Phabel\Traverser;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;

use function Amp\Parallel\Worker\pool;

require_once 'vendor/autoload.php';

$canCoverage = false;
try {
    $filter = new Filter;
    $filter->includeDirectory(\realpath(__DIR__.'/../src'));
    (new Selector)->forLineCoverage($filter);
    $canCoverage = true;
} catch (\Throwable $e) {
}

if ($canCoverage) {
    pool(new DefaultPool(\getenv('CI') ? 3 : \count(Php::VERSIONS) + 2));
}

const BASE = \PhabelTest\Target\TypeHintReplacerTest::class;

$packages = [];
$packagesSecondary = [];
foreach (Php::VERSIONS as $version) {
    $types = [
        'callable', 'iterable', 'object', 'self', 'static',
        'int', 'float', 'array', 'string', 'bool',
        \Generator::class,
        \str_replace("Target", "Target$version", \PhabelTest\Target\TypeHintReplacerTest::class),
    ];
    foreach (\glob("testsGenerated/Target/TypeHintReplacer*") as $test) {
        \preg_match("~(TypeHintReplacer\d+Test)~", $test, $matches);
        $types []= BASE;
        $types []= $r = \str_replace("TypeHintReplacerTest", $matches[1], BASE);
        $types []= \str_replace("Target", "Target$version", BASE);
        $types []= \str_replace("Target", "Target$version", $r);
    }
    `rm -rf "testsGenerated/Target$version"`;
    `rm -rf "testsGenerated/Target10$version"`;
    $packages += (new Traverser(EventHandler::create()))
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => $version],
            TypeHintReplacer::class => ['union' => true, 'nullable' => true, 'return' => true, 'types' => $types]
        ])
        ->setInput('testsGenerated/Target')
        ->setOutput("testsGenerated/Target$version")
        ->setCoverage("expr$version")
        ->runAsync();
    $packagesSecondary += (new Traverser(EventHandler::create()))
        ->setPlugins([
            PhabelTestGenerator::class => ['target' => 1000+$version]
        ])
        ->setInput("testsGenerated/Target$version")
        ->setOutput("testsGenerated/Target10$version")
        ->setCoverage("expr10$version")
        ->runAsync();
}

$binary = PHP_SAPI === 'phpdbg' ? PHP_BINARY." -qrr" : PHP_BINARY;

$current = (int) (PHP_MAJOR_VERSION.PHP_MINOR_VERSION);
foreach (\glob("testsGenerated/*/*.php") as $i => $test) {
    $version = (int) \substr(\basename(\dirname($test)), 6);
    $version = $version ?: 80;
    if ($version > $current) {
        continue;
    }
    echo $test.PHP_EOL;

    \passthru("$binary vendor/bin/phpunit -c phpunit-expr.xml $test --coverage-php=coverage/phpunitExpr$i.php", $ret);
    if ($ret) {
        die($ret);
    }
}
