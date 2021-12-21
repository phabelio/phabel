<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test121floatobject(float|object $data): float|object { return $data; }
function testRet121floatobject($data): float|object { return $data; }
function test122floatobject(float|object $data): float|object { return $data; }
function testRet122floatobject($data): float|object { return $data; }
function test123floatobject(float|object $data): float|object { return $data; }
function testRet123floatobject($data): float|object { return $data; }
function test124object(?object $data): ?object { return $data; }
function testRet124object($data): ?object { return $data; }
function test125object(?object $data): ?object { return $data; }
function testRet125object($data): ?object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer25Test extends TestCase
{
    /**
     * @dataProvider returnDataProvider
     */
    public function testRet(callable $c, $data, $wrongData, string $exception) {
        $this->assertTrue($data == $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): float|object => $data, "123.123", null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, "123.123", null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet121floatobject'], "123.123", null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet121floatobject'], "123.123", null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet121floatobject', "123.123", null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet122floatobject'], new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet122floatobject'], new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet122floatobject', new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet123floatobject'], $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet123floatobject'], $this, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet123floatobject', $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): ?object => $data, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet124object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet124object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet124object', new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): ?object => $data, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet125object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet125object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet125object', $this, 0, '~.*Return value must be of type \\?object, int returned~']];
;
    }
    /**
     * @dataProvider paramDataProvider
     */
    public function test(callable $c, $data, $wrongData, string $exception) {
        $this->assertTrue($data == $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function paramDataProvider(): array
    {
        return [
[fn (float|object $data): float|object => $data, "123.123", null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, "123.123", null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test121floatobject'], "123.123", null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test121floatobject'], "123.123", null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test121floatobject', "123.123", null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test122floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test122floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test122floatobject', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test123floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test123floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test123floatobject', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (?object $data): ?object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test124object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test124object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test124object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (?object $data): ?object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test125object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test125object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test125object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test121floatobject(float|object $data): float|object { return $data; }
private static function testRet121floatobject($data): float|object { return $data; }
private static function test122floatobject(float|object $data): float|object { return $data; }
private static function testRet122floatobject($data): float|object { return $data; }
private static function test123floatobject(float|object $data): float|object { return $data; }
private static function testRet123floatobject($data): float|object { return $data; }
private static function test124object(?object $data): ?object { return $data; }
private static function testRet124object($data): ?object { return $data; }
private static function test125object(?object $data): ?object { return $data; }
private static function testRet125object($data): ?object { return $data; }

}