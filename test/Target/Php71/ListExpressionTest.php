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
        $arr = ['a', ['b' => 'bb', 'c' => ['d', 'e']]];
        $this->assertEquals($arr, [$a, ['b' => $b, 'c' => [$d, $e]]] = $arr);
        $this->assertEquals('a', $a);
        $this->assertEquals('bb', $b);
        $this->assertEquals('d', $d);
        $this->assertEquals('e', $e);
    }
}
