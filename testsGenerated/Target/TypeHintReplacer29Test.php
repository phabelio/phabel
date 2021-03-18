<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test141string(?string $data): ?string { return $data; }
function testRet141string($data): ?string { return $data; }
function test142string(?string $data): ?string { return $data; }
function testRet142string($data): ?string { return $data; }
function test143string(?string $data): ?string { return $data; }
function testRet143string($data): ?string { return $data; }
function test144string(?string $data): ?string { return $data; }
function testRet144string($data): ?string { return $data; }
function test145string(?string $data): ?string { return $data; }
function testRet145string($data): ?string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer29Test extends TestCase
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
[fn ($data): ?string => $data, 123.123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 123.123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet141string'], 123.123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet141string'], 123.123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet141string', 123.123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, 1e3, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 1e3, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet142string'], 1e3, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet142string'], 1e3, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet142string', 1e3, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, true, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, true, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet143string'], true, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet143string'], true, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet143string', true, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, false, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, false, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet144string'], false, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet144string'], false, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet144string', false, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet145string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet145string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet145string', null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~']];
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
[fn (?string $data): ?string => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test141string'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test141string'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test141string', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test142string'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test142string'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test142string', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test143string'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test143string'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test143string', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test144string'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test144string'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test144string', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test145string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test145string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test145string', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test141string(?string $data): ?string { return $data; }
private static function testRet141string($data): ?string { return $data; }
private static function test142string(?string $data): ?string { return $data; }
private static function testRet142string($data): ?string { return $data; }
private static function test143string(?string $data): ?string { return $data; }
private static function testRet143string($data): ?string { return $data; }
private static function test144string(?string $data): ?string { return $data; }
private static function testRet144string($data): ?string { return $data; }
private static function test145string(?string $data): ?string { return $data; }
private static function testRet145string($data): ?string { return $data; }

}