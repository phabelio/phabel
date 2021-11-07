<?php

namespace Phabel;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser as PhpStanDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;

/**
 * A wrapper class around phpstan's PHPDoc parser.
 */
class PhpDocParser
{
    private Lexer $lexer;
    private PhpStanDocParser $parser;
    private TypeParser $typeParser;

    public function __construct()
    {
        $constParser = new ConstExprParser;
        $this->parser = new PhpStanDocParser(new TypeParser($constParser), $constParser);
        $this->typeParser = new TypeParser($constParser);
        $this->lexer = new Lexer;
    }

    /**
     * Parse a phpdoc.
     *
     * @param string|null $phpdoc
     * @return PhpDocNode|null
     */
    public function parsePhpDoc(?string $phpdoc): ?PhpDocNode
    {
        if (!$phpdoc) {
            return null;
        }
        return $this->parser->parse(new TokenIterator($this->lexer->tokenize($phpdoc)));
    }

    /**
     * Parse a phpdoc type declaration.
     *
     * @param string $type
     * @return TypeNode
     */
    public function parseType(string $type): TypeNode
    {
        return $this->typeParser->parse(new TokenIterator($this->lexer->tokenize($type)));
    }
}
