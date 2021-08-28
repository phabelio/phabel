<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test116floatobject(float|object $data): float|object { return $data; }
function testRet116floatobject($data): float|object { return $data; }
function test117floatobject(float|object $data): float|object { return $data; }
function testRet117floatobject($data): float|object { return $data; }
function test118floatobject(float|object $data): float|object { return $data; }
function testRet118floatobject($data): float|object { return $data; }
function test119floatobject(float|object $data): float|object { return $data; }
function testRet119floatobject($data): float|object { return $data; }
function test120floatobject(float|object $data): float|object { return $data; }
function testRet120floatobject($data): float|object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer24Test extends TestCase
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
[fn ($data): float|object => $data, 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet116floatobject'], 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet116floatobject'], 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet116floatobject', 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet117floatobject'], 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet117floatobject'], 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet117floatobject', 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, true, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, true, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet118floatobject'], true, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet118floatobject'], true, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet118floatobject', true, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, false, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, false, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet119floatobject'], false, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet119floatobject'], false, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet119floatobject', false, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, '123', null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, '123', null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet120floatobject'], '123', null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet120floatobject'], '123', null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet120floatobject', '123', null, '~.*Return value must be of type object\\|float, null returned~']];
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
[fn (float|object $data): float|object => $data, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test116floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test116floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test116floatobject', 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test117floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test117floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test117floatobject', 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test118floatobject'], true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test118floatobject'], true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test118floatobject', true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test119floatobject'], false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test119floatobject'], false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test119floatobject', false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, '123', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, '123', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test120floatobject'], '123', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test120floatobject'], '123', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test120floatobject', '123', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test116floatobject(float|object $data): float|object { return $data; }
private static function testRet116floatobject($data): float|object { return $data; }
private static function test117floatobject(float|object $data): float|object { return $data; }
private static function testRet117floatobject($data): float|object { return $data; }
private static function test118floatobject(float|object $data): float|object { return $data; }
private static function testRet118floatobject($data): float|object { return $data; }
private static function test119floatobject(float|object $data): float|object { return $data; }
private static function testRet119floatobject($data): float|object { return $data; }
private static function test120floatobject(float|object $data): float|object { return $data; }
private static function testRet120floatobject($data): float|object { return $data; }

}