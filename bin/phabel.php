#!/usr/bin/env php
<?php

use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Traverser;

require __DIR__.'/../vendor/autoload.php';

set_error_handler(
    function ($errno = 0, $errstr = null, $errfile = null, $errline = null): bool {
        // If error is suppressed with @, don't throw an exception
        if (\error_reporting() === 0) {
            return false;
        }
        throw new Exception($errstr, $errno);
    }
);

$a = new Graph;
$a->addPlugin(Php::class, [], $a->getPackageContext());

$p = new Traverser($a->flatten());
array_shift($argv);
foreach ($argv as $v) {
    var_dump($v);
    $p->traverse($v, $v);
}
