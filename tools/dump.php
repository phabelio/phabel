<?php

/**
 * Dump AST of file.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

require 'vendor/autoload.php';
if ($argc < 2) {
    echo "Usage: {$argv[0]} file.php\n";
    die(1);
}
$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
//$parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP5);
\var_dump($a = $parser->parse(\Phabel\Target\Php71\Polyfill::file_get_contents($argv[1])));
\var_dumP((new Standard())->prettyPrint($a));
