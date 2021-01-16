<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test56float(?float $data): ?float { return $data; }
function testRet56float($data): ?float { return $data; }
function test57float(?float $data): ?float { return $data; }
function testRet57float($data): ?float { return $data; }
function test58float(?float $data): ?float { return $data; }
function testRet58float($data): ?float { return $data; }
function test59floatobject(float|object $data): float|object { return $data; }
function testRet59floatobject($data): float|object { return $data; }
function test60floatobject(float|object $data): float|object { return $data; }
function testRet60floatobject($data): float|object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer12Test extends TestCase
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
[fn ($data): ?float => $data, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet56float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet56float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet56float', 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet57float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet57float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet57float', 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet58float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet58float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet58float', null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): float|object => $data, 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet59floatobject'], 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet59floatobject'], 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet59floatobject', 123.123, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet60floatobject'], 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet60floatobject'], 1e3, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet60floatobject', 1e3, null, '~.*Return value must be of type object\\|float, null returned~']];
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
[fn (?float $data): ?float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test56float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test56float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test56float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test57float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test57float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test57float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test58float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test58float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test58float', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (float|object $data): float|object => $data, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test59floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test59floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test59floatobject', 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test60floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test60floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test60floatobject', 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test56float(?float $data): ?float { return $data; }
private static function testRet56float($data): ?float { return $data; }
private static function test57float(?float $data): ?float { return $data; }
private static function testRet57float($data): ?float { return $data; }
private static function test58float(?float $data): ?float { return $data; }
private static function testRet58float($data): ?float { return $data; }
private static function test59floatobject(float|object $data): float|object { return $data; }
private static function testRet59floatobject($data): float|object { return $data; }
private static function test60floatobject(float|object $data): float|object { return $data; }
private static function testRet60floatobject($data): float|object { return $data; }

}