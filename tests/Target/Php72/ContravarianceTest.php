<?php

namespace PhabelTest\Target\Php72;

use PhabelTest\Target\Php72\Contravariance\Cls;
use PhabelTest\Target\Php72\Contravariance\Cls3;
use PhabelTest\Target\Php72\Contravariance\OtherCls1;
use PhabelTest\Target\Php72\Contravariance\OtherCls2;
use PhabelTest\Target\Php72\Contravariance\OtherCls3;
use PhabelTest\Target\Php72\Contravariance\OtherClsNative;
use PHPUnit\Framework\TestCase;

/**
 * Tests type restriction and widening
 */
class ContravarianceTest extends TestCase
{
    public function test()
    {
        $obj = new Cls;
        $this->assertEquals('test', $obj->testCanRestrict1());
        $this->assertEquals(0, $obj->testCanRestrict2());

        $this->assertEquals('test', $obj->testCanWiden1('test'));
        $this->assertEquals(0.1, $obj->testCanWiden2(0.1));
        $this->assertEquals($obj, $obj->testCanWiden2($obj));

        $obj = new Cls3;
        $this->assertEquals('testCanRestrict1', $obj->testCanRestrict1());
        $this->assertEquals('testCanRestrict2', $obj->testCanRestrict2());
        $this->assertEquals('testCanWiden1', $obj->testCanWiden1(null));
        $this->assertEquals('testCanWiden2', $obj->testCanWiden2(null));

        $this->assertInstanceOf(OtherCls1::class, new OtherCls1);
        $this->assertInstanceOf(OtherCls1::class, OtherCls1::get());
        $this->assertInstanceOf(OtherCls2::class, OtherCls2::get());
        $this->assertInstanceOf(OtherCls3::class, OtherCls3::get());

        $this->assertInstanceOf(OtherClsNative::class, OtherClsNative::get());
    }
}
