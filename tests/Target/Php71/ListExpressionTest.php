<?php

namespace PhabelTest\Target\Php71;

use PHPUnit\Framework\TestCase;

/**
 * Polyfills list expression return value.
 */
class ListExpressionTest extends TestCase
{
    public function test()
    {
        $bK = 'b';
        $cK = 'c';
        $arr = ['a', ['b' => 'bb', 'c' => ['d', 'e']]];
        $this->assertEquals($arr, [$a, [$bK => $b, $cK => [$d, $e]]] = $arr);
        $this->assertEquals('a', $a);
        $this->assertEquals('bb', $b);
        $this->assertEquals('d', $d);
        $this->assertEquals('e', $e);
    }
    public function testEmpty()
    {
        $arr = ['a', 'b', 'c', 'd'];
        $this->assertEquals($arr, [$a, $b, , $d] = $arr);
        $this->assertEquals('a', $a);
        $this->assertEquals('b', $b);
        $this->assertEquals('d', $d);
    }
}
