<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Lexer\TokenEmulator;

use PhabelVendor\PhpParser\Lexer\Emulative;
final class AwaitTokenEmulator extends KeywordEmulator
{
    public function getPhpVersion() : string
    {
        return Emulative::PHP_UNOBTANIUM;
    }
    public function getKeywordString() : string
    {
        return 'await';
    }
    public function getKeywordToken() : int
    {
        return \T_YIELD;
    }
}
