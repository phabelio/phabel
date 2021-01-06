<?php

use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Traverser;

if (!\class_exists(Php::class)) {
    require __DIR__.'/../vendor/autoload.php';
}

if ($argc !== 3) {
    $help = <<< EOF
Usage: ${argv[0]} {options} input output

input - Input file/directory
output - Output file/directory

EOF;
    echo $help;
    die(1);
}

\set_error_handler(
    function ($errno = 0, $errstr = null, $errfile = null, $errline = null): bool {
        // If error is suppressed with @, don't throw an exception
        if (\error_reporting() === 0) {
            return false;
        }
        throw new \RuntimeException($errstr, $errno);
    }
);

$graph = new Graph;
$graph->addPlugin(Php::class, ['target' => getenv('PHABEL_TARGET') ?: Php::DEFAULT_TARGET], $graph->getPackageContext());

[$plugins, $packages] = $graph->flatten();
$p = new Traverser($plugins);

$input = $argv[1];
$output = $argv[2];

if (\is_file($input) && \is_file($output)) {
    $p->traverse($input, $output);
    return;
}

if (!\file_exists($output)) {
    \mkdir($output, 0777, true);
}

$it = new \RecursiveDirectoryIterator($input, RecursiveDirectoryIterator::SKIP_DOTS);
$ri = new \RecursiveIteratorIterator($it, RecursiveIteratorIterator::SELF_FIRST);
foreach ($ri as $file) {
    $targetPath = $output.DIRECTORY_SEPARATOR.$ri->getSubPathname();
    if ($file->isDir()) {
        if (!\file_exists($targetPath)) {
            \mkdir($targetPath, 0777, true);
        }
    } elseif ($file->isFile()) {
        if ($file->getExtension() == 'php') {
            echo("Transforming ".$file->getRealPath().PHP_EOL);
            $p->traverse($file->getRealPath(), $targetPath);
        } else {
            \copy($file->getRealPath(), $targetPath);
        }
    }
}


if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint");
    }
    echo "Please run $cmd".PHP_EOL;
}
