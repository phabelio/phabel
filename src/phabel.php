<?php

use Phabel\EventHandler;
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

$packages = (new Traverser(new class extends EventHandler {
    public function onBeginDirectoryTraversal(int $total): void
    {
        echo "Starting directory traversal...".PHP_EOL;
    }
    public function onEndAstTraversal(string $file, int $iterations): void
    {
        echo "Processed $file in $iterations iterations!".PHP_EOL;
    }
    public function onEnd(): void
    {
        echo "Done!".PHP_EOL;
    }
}))
    ->setPlugins([
        Php::class => ['target' => \getenv('PHABEL_TARGET') ?: Php::DEFAULT_TARGET]
    ])
    ->setInput($argv[1])
    ->setOutput($argv[2])
    ->setCoverage(\getenv('PHABEL_COVERAGE') ?: '')
    ->run();

if (!empty($packages)) {
    $cmd = "composer require --dev ";
    foreach ($packages as $package => $constraint) {
        $cmd .= \escapeshellarg("$package:$constraint")." ";
    }
    echo "All done, OK!".PHP_EOL;
    echo "Please run the following command to install required development dependencies:".PHP_EOL.PHP_EOL;
    echo $cmd.PHP_EOL.PHP_EOL;
}
