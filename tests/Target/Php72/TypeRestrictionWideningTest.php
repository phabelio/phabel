<?php

namespace PhabelTest\Target\Php72;

use PHPUnit\Framework\TestCase;

/**
 * Tests type restriction and widening
 */
class TypeRestrictionWideningTest extends TestCase
{
    public function test()
    {
        $obj = new Cls;
        $this->assertEquals('test', $obj->testCanRestrict1());
        $this->assertEquals(0, $obj->testCanRestrict2());

        $this->assertEquals('test', $obj->testCanWiden1('test'));
        $this->assertEquals(0.1, $obj->testCanWiden2(0.1));
        $this->assertEquals($obj, $obj->testCanWiden2($obj));
    }
}
