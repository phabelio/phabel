<?php

namespace PhabelTest\Plugin;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer extends TestCase
{
    private function testStr(string $data): string
    {
        return $data;
    }
    private function testStrRet($data): string
    {
        return $data;
    }
    private function testInt(int $data): int
    {
        return $data;
    }
    private function testIntRet($data): int
    {
        return $data;
    }
    private function testFloat(float $data): float
    {
        return $data;
    }
    private function testFloatRet($data): float
    {
        return $data;
    }
    private function testBool(bool $data): bool
    {
        return $data;
    }
    private function testBoolRet($data): bool
    {
        return $data;
    }
    public function test()
    {
        $this->assertEquals('uwu', $this->testStr('uwu'));
        $this->assertEquals(123, $this->testInt(123));
        $this->assertEquals(123.123, $this->testFloat(123.123));
        $this->assertTrue($this->testBool(true));
        $this->assertFalse($this->testBool(false));

        $this->assertEquals('uwu', $this->testStrRet('uwu'));
        $this->assertEquals(123, $this->testIntRet(123));
        $this->assertEquals(123.123, $this->testFloatRet(123.123));
        $this->assertTrue($this->testBoolRet(true));
        $this->assertFalse($this->testBoolRet(false));
    }
}
