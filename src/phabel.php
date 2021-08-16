<?php

namespace Phabel;

use Phabel\Cli\Formatter;
use Phabel\Commands\Run;
use Phabel\Commands\Tag;
use Symfony\Component\Console\Application;

if (!\class_exists(Run::class)) {
    require __DIR__.'/../vendor/autoload.php';
}

$app = new Application(Formatter::banner());
$app->add(new Tag());
$app->add(new Run());
$app->run();
