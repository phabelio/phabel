<?php

namespace PhabelTest\Target\Php74;

use Phabel\Context;
use PHPUnit\Framework\TestCase;
use Phabel\Traverser;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosure extends TestCase
{
    public function test()
    {
        $r = true;

        $this->assertTrue((fn () => true)());
        $this->assertTrue((fn ($data) => $data)(true));
        $this->assertTrue((fn () => $r)());

        $this->assertFalse((fn () => $r = false)());
        $this->assertTrue((fn () => $r)());
    }
}
