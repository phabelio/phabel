<?php

use Phabel\Cli\EventHandler;
use Phabel\Target\Php;
use Phabel\Traverser;

require 'vendor/autoload.php';
require 'functions.php';

$tail = $argv[1] ?? '';
foreach (Php::VERSIONS as $version) {
    if ($tail === "-$version") {
        break;
    }
}

$packages = (new Traverser(EventHandler::create()))
    ->setPlugins([Php::class => ['target' => $version]])
    ->setInput('vendor')
    ->setOutput('vendor')
    ->setCoverage('coverage/convertVendor.php')
    ->run(\getenv('PHABEL_PARALLEL') ?: 1);
