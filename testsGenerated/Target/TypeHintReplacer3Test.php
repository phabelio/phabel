<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test11float(float $data): float { return $data; }
function testRet11float($data): float { return $data; }
function test12float(float $data): float { return $data; }
function testRet12float($data): float { return $data; }
function test13object(object $data): object { return $data; }
function testRet13object($data): object { return $data; }
function test14object(object $data): object { return $data; }
function testRet14object($data): object { return $data; }
function test15string(string $data): string { return $data; }
function testRet15string($data): string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer3Test extends TestCase
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
[fn ($data): float => $data, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet11float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet11float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet11float', 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet12float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet12float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet12float', 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): object => $data, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet13object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet13object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet13object', new class{}, 0, '~.*Return value must be of type object, int returned~'],
[fn ($data): object => $data, $this, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, $this, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet14object'], $this, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet14object'], $this, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet14object', $this, 0, '~.*Return value must be of type object, int returned~'],
[fn ($data): string => $data, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet15string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet15string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet15string', 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~']];
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
[fn (float $data): float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test11float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test11float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test11float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test12float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test12float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test12float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (object $data): object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test13object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test13object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test13object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[fn (object $data): object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test14object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test14object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test14object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[fn (string $data): string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test15string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test15string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test15string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test11float(float $data): float { return $data; }
private static function testRet11float($data): float { return $data; }
private static function test12float(float $data): float { return $data; }
private static function testRet12float($data): float { return $data; }
private static function test13object(object $data): object { return $data; }
private static function testRet13object($data): object { return $data; }
private static function test14object(object $data): object { return $data; }
private static function testRet14object($data): object { return $data; }
private static function test15string(string $data): string { return $data; }
private static function testRet15string($data): string { return $data; }

}