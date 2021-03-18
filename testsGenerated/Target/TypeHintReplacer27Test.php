<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test131objectstring(object|string $data): object|string { return $data; }
function testRet131objectstring($data): object|string { return $data; }
function test132objectstring(object|string $data): object|string { return $data; }
function testRet132objectstring($data): object|string { return $data; }
function test133objectstring(object|string $data): object|string { return $data; }
function testRet133objectstring($data): object|string { return $data; }
function test134objectstring(object|string $data): object|string { return $data; }
function testRet134objectstring($data): object|string { return $data; }
function test135objectstring(object|string $data): object|string { return $data; }
function testRet135objectstring($data): object|string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer27Test extends TestCase
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
[fn ($data): object|string => $data, 123, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, 123, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet131objectstring'], 123, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet131objectstring'], 123, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet131objectstring', 123, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, -1, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, -1, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet132objectstring'], -1, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet132objectstring'], -1, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet132objectstring', -1, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, 123.123, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, 123.123, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet133objectstring'], 123.123, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet133objectstring'], 123.123, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet133objectstring', 123.123, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, 1e3, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, 1e3, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet134objectstring'], 1e3, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet134objectstring'], 1e3, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet134objectstring', 1e3, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, true, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, true, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet135objectstring'], true, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet135objectstring'], true, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet135objectstring', true, null, '~.*Return value must be of type object\\|string, null returned~']];
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
[fn (object|string $data): object|string => $data, 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test131objectstring'], 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test131objectstring'], 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test131objectstring', 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test132objectstring'], -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test132objectstring'], -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test132objectstring', -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test133objectstring'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test133objectstring'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test133objectstring', 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test134objectstring'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test134objectstring'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test134objectstring', 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test135objectstring'], true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test135objectstring'], true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test135objectstring', true, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test131objectstring(object|string $data): object|string { return $data; }
private static function testRet131objectstring($data): object|string { return $data; }
private static function test132objectstring(object|string $data): object|string { return $data; }
private static function testRet132objectstring($data): object|string { return $data; }
private static function test133objectstring(object|string $data): object|string { return $data; }
private static function testRet133objectstring($data): object|string { return $data; }
private static function test134objectstring(object|string $data): object|string { return $data; }
private static function testRet134objectstring($data): object|string { return $data; }
private static function test135objectstring(object|string $data): object|string { return $data; }
private static function testRet135objectstring($data): object|string { return $data; }

}