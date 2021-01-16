<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test61floatobject(float|object $data): float|object { return $data; }
function testRet61floatobject($data): float|object { return $data; }
function test62floatobject(float|object $data): float|object { return $data; }
function testRet62floatobject($data): float|object { return $data; }
function test63object(?object $data): ?object { return $data; }
function testRet63object($data): ?object { return $data; }
function test64object(?object $data): ?object { return $data; }
function testRet64object($data): ?object { return $data; }
function test65object(?object $data): ?object { return $data; }
function testRet65object($data): ?object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer13Test extends TestCase
{
    /**
     * @dataProvider returnDataProvider
     */
    public function testRet(callable $c, $data, $wrongData, string $exception) {
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): float|object => $data, new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet61floatobject'], new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet61floatobject'], new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet61floatobject', new class{}, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet62floatobject'], $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet62floatobject'], $this, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet62floatobject', $this, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): ?object => $data, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet63object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet63object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet63object', new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): ?object => $data, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet64object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet64object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet64object', $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): ?object => $data, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet65object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet65object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet65object', null, 0, '~.*Return value must be of type \\?object, int returned~']];
;
    }
    /**
     * @dataProvider paramDataProvider
     */
    public function test(callable $c, $data, $wrongData, string $exception) {
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function paramDataProvider(): array
    {
        return [
[fn (float|object $data): float|object => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test61floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test61floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test61floatobject', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test62floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test62floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test62floatobject', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (?object $data): ?object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test63object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test63object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test63object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (?object $data): ?object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test64object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test64object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test64object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (?object $data): ?object => $data, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test65object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test65object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test65object', null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test61floatobject(float|object $data): float|object { return $data; }
private static function testRet61floatobject($data): float|object { return $data; }
private static function test62floatobject(float|object $data): float|object { return $data; }
private static function testRet62floatobject($data): float|object { return $data; }
private static function test63object(?object $data): ?object { return $data; }
private static function testRet63object($data): ?object { return $data; }
private static function test64object(?object $data): ?object { return $data; }
private static function testRet64object($data): ?object { return $data; }
private static function test65object(?object $data): ?object { return $data; }
private static function testRet65object($data): ?object { return $data; }

}