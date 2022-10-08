<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ConstExprReplacerTest extends TestCase
{
    public const A = 123;
    private const B = ['321'];
    protected const C = [null, 'magic', self::A, ...self::B];
    private function testReturn(array $return = [null, 'magic', self::A, ...self::B]): array {
        return $return;
    }
    public function test()
    {
        $this->assertEquals([null, 'magic', 123, '321'], self::C);
        $this->assertEquals([null, 'magic', 123, '321'], $this->testReturn());
    }
}
