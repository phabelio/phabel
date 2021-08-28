<?php

namespace Phabel\PhpParser;

class ParserFactory
{
    const PREFER_PHP7 = 1;
    const PREFER_PHP5 = 2;
    const ONLY_PHP7 = 3;
    const ONLY_PHP5 = 4;
    /**
     * Creates a Parser instance, according to the provided kind.
     *
     * @param int        $kind  One of ::PREFER_PHP7, ::PREFER_PHP5, ::ONLY_PHP7 or ::ONLY_PHP5
     * @param Lexer|null $lexer Lexer to use. Defaults to emulative lexer when not specified
     * @param array      $parserOptions Parser options. See ParserAbstract::__construct() argument
     *
     * @return Parser The parser instance
     */
    public function create($kind, Lexer $lexer = null, array $parserOptions = [])
    {
        if (!\is_int($kind)) {
            if (!(\is_bool($kind) || \is_numeric($kind))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($kind) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($kind) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $kind = (int) $kind;
            }
        }
        if (null === $lexer) {
            $lexer = new Lexer\Emulative();
        }
        switch ($kind) {
            case self::PREFER_PHP7:
                $phabelReturn = new Parser\Multiple([new Parser\Php7($lexer, $parserOptions), new Parser\Php5($lexer, $parserOptions)]);
                if (!$phabelReturn instanceof Parser) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Parser, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            case self::PREFER_PHP5:
                $phabelReturn = new Parser\Multiple([new Parser\Php5($lexer, $parserOptions), new Parser\Php7($lexer, $parserOptions)]);
                if (!$phabelReturn instanceof Parser) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Parser, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            case self::ONLY_PHP7:
                $phabelReturn = new Parser\Php7($lexer, $parserOptions);
                if (!$phabelReturn instanceof Parser) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Parser, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            case self::ONLY_PHP5:
                $phabelReturn = new Parser\Php5($lexer, $parserOptions);
                if (!$phabelReturn instanceof Parser) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Parser, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            default:
                throw new \LogicException('Kind must be one of ::PREFER_PHP7, ::PREFER_PHP5, ::ONLY_PHP7 or ::ONLY_PHP5');
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Parser, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
}
