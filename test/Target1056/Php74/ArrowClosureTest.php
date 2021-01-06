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
        $phabel_2b33e00c9589347a = function () {
            return true;
        };
        $this->assertTrue($phabel_2b33e00c9589347a());
        $phabel_61b8e6a82a0679e7 = function ($data) {
            return $data;
        };
        $this->assertTrue($phabel_61b8e6a82a0679e7(true));
        $phabel_a664637c56f769bd = function () {
            return $r;
        };
        $this->assertTrue($phabel_a664637c56f769bd());
        $phabel_d59cdf6209dee987 = function () {
            return $r = false;
        };
        $this->assertFalse($phabel_d59cdf6209dee987());
        $phabel_a5831685d3367151 = function () {
            return $r;
        };
        $this->assertTrue($phabel_a5831685d3367151());
        $phabel_ac51914b649f9a7f = function () {
            return $this;
        };
        $this->assertEquals($this, $phabel_ac51914b649f9a7f());
        $phabel_51c7e803bafe3035 = static function () {
            $phabelReturn = isset($this);
            if (!\is_bool($phabelReturn)) {
                throw new \TypeError('Return value of' . __METHOD__ . ' must be of type bool, ' . \gettype($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace(0));
            }
            return $phabelReturn;
        };
        $this->assertFalse($phabel_51c7e803bafe3035());
    }
}