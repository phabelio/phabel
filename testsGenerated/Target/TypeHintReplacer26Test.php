<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test126object(?object $data): ?object { return $data; }
function testRet126object($data): ?object { return $data; }
function test127objectstring(object|string $data): object|string { return $data; }
function testRet127objectstring($data): object|string { return $data; }
function test128objectstring(object|string $data): object|string { return $data; }
function testRet128objectstring($data): object|string { return $data; }
function test129objectstring(object|string $data): object|string { return $data; }
function testRet129objectstring($data): object|string { return $data; }
function test130objectstring(object|string $data): object|string { return $data; }
function testRet130objectstring($data): object|string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer26Test extends TestCase
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
[fn ($data): ?object => $data, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet126object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet126object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet126object', null, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): object|string => $data, new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet127objectstring'], new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet127objectstring'], new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet127objectstring', new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet128objectstring'], $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet128objectstring'], $this, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet128objectstring', $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet129objectstring'], 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet129objectstring'], 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet129objectstring', 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, new class{public function __toString() { return "lmao"; }}, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, new class{public function __toString() { return "lmao"; }}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet130objectstring'], new class{public function __toString() { return "lmao"; }}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet130objectstring'], new class{public function __toString() { return "lmao"; }}, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet130objectstring', new class{public function __toString() { return "lmao"; }}, null, '~.*Return value must be of type object\\|string, null returned~']];
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
[fn (?object $data): ?object => $data, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test126object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test126object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test126object', null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (object|string $data): object|string => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test127objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test127objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test127objectstring', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test128objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test128objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test128objectstring', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test129objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test129objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test129objectstring', 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, new class{public function __toString() { return "lmao"; }}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, new class{public function __toString() { return "lmao"; }}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test130objectstring'], new class{public function __toString() { return "lmao"; }}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test130objectstring'], new class{public function __toString() { return "lmao"; }}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test130objectstring', new class{public function __toString() { return "lmao"; }}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test126object(?object $data): ?object { return $data; }
private static function testRet126object($data): ?object { return $data; }
private static function test127objectstring(object|string $data): object|string { return $data; }
private static function testRet127objectstring($data): object|string { return $data; }
private static function test128objectstring(object|string $data): object|string { return $data; }
private static function testRet128objectstring($data): object|string { return $data; }
private static function test129objectstring(object|string $data): object|string { return $data; }
private static function testRet129objectstring($data): object|string { return $data; }
private static function test130objectstring(object|string $data): object|string { return $data; }
private static function testRet130objectstring($data): object|string { return $data; }

}