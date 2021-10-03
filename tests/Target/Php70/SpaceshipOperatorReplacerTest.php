<?php

namespace PhabelTest\Target\Php70;

use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PHPUnit\Framework\TestCase;

/**
 * Polyfill spaceship operator.
 */
class SpaceshipOperatorReplacerTest extends TestCase
{
    public function test()
    {
        $this->assertEquals(0, 123 <=> 123);
        $this->assertEquals(-1, 122 <=> 123);
        $this->assertEquals(-1, 123 <=> 124);
    }
}
