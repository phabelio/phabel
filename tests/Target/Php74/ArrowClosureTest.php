<?php

namespace PhabelTest\Target\Php74;

use PhpParser\Node\Expr\Closure;
use PHPUnit\Framework\TestCase;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosureTest extends TestCase
{
    public function test()
    {
        $r = true;

        $this->assertTrue((fn () => true)());
        $this->assertTrue((fn ($data) => $data)(true));
        $this->assertTrue((fn () => $r)());

        $this->assertFalse((fn () => $r = false)());
        $this->assertTrue((fn () => $r)());

        $this->assertEquals($this, (fn () => $this)());
        $this->assertFalse((static fn (): bool => isset($this))());
        
        foreach (['test'] as $v) {
            $f = fn () => $v;
            $this->assertEquals('test', $f());
        }
    }
}
