<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ClosureCallReplacer extends TestCase
{
    public function test()
    {
        $this->assertEquals('uwu', (fn (string $d): string => $d)('uwu'));
    }
}
