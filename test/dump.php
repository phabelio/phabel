<?php

use PhpParser\ParserFactory;

require 'vendor/autoload.php';

if ($argc < 2) {
    echo("Usage: {$argv[0]} file.php\n");
    die(1);
}

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

\var_dump($parser->parse(\file_get_contents($argv[1])));
