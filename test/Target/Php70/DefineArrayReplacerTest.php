<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Const_;

if (!defined('PHABEL_ARRAY_TEST')) {
    define('PHABEL_ARRAY_TEST', ['uwu']);
}

/**
 * Converts define() arrays into const arrays.
 */
class DefineArrayReplacer extends TestCase
{
    public function test()
    {
        $this->assertEquals('uwu', PHABEL_ARRAY_TEST[0]);
    }
}
