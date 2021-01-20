<?php

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

$packages = Traverser::run(
    [
        Php::class => ['target' => \getenv('PHABEL_TARGET') ?: Php::DEFAULT_TARGET]
    ],
    $argv[1],
    $argv[2],
    \getenv('PHABEL_COVERAGE') ?: ''
);

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint")." ";
    }
    echo "All done, OK!".PHP_EOL;
    echo "Please run the following command to install required development dependencies:".PHP_EOL.PHP_EOL;
    echo $cmd.PHP_EOL.PHP_EOL;
}
