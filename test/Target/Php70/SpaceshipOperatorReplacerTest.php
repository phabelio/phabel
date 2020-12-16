<?php

namespace PhabelTest\Target\Php70;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\BinaryOp\Spaceship;
use PhpParser\Node\Expr\StaticCall;

/**
 * Polyfill spaceship operator.
 */
class SpaceshipOperatorReplacer extends TestCase
{
    public function test() {
        $this->assertEquals(0, 123 <=> 123);
        $this->assertEquals(-1, 122 <=> 123);
        $this->assertEquals(-1, 123 <=> 124);
    }
}
