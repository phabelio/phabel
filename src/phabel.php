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

Options:

EOF;
    echo $help;
    die(1);
}

set_error_handler(
    function ($errno = 0, $errstr = null, $errfile = null, $errline = null): bool {
        // If error is suppressed with @, don't throw an exception
        if (\error_reporting() === 0) {
            return false;
        }
        throw new \RuntimeException($errstr, $errno);
    }
);

$graph = new Graph;
$graph->addPlugin(Php::class, [], $graph->getPackageContext());

$p = new Traverser($graph->flatten());
array_shift($argv);
foreach ($argv as $v) {
    var_dump($v);
    $p->traverse($v, $v);
}
