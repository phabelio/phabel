<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test16iterable(iterable $data): iterable { return $data; }
function testRet16iterable($data): iterable { return $data; }
function test17float(float $data): float { return $data; }
function testRet17float($data): float { return $data; }
function test18float(float $data): float { return $data; }
function testRet18float($data): float { return $data; }
function test19float(float $data): float { return $data; }
function testRet19float($data): float { return $data; }
function test20float(float $data): float { return $data; }
function testRet20float($data): float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer4Test extends TestCase
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
[fn ($data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet16iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet16iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet16iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): float => $data, 123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet17float'], 123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet17float'], 123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet17float', 123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, -1, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, -1, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet18float'], -1, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet18float'], -1, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet18float', -1, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet19float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet19float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet19float', 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet20float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet20float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet20float', 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~']];
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
[fn (iterable $data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test16iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test16iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test16iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (float $data): float => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test17float'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test17float'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test17float', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test18float'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test18float'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test18float', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test19float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test19float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test19float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test20float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test20float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test20float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test16iterable(iterable $data): iterable { return $data; }
private static function testRet16iterable($data): iterable { return $data; }
private static function test17float(float $data): float { return $data; }
private static function testRet17float($data): float { return $data; }
private static function test18float(float $data): float { return $data; }
private static function testRet18float($data): float { return $data; }
private static function test19float(float $data): float { return $data; }
private static function testRet19float($data): float { return $data; }
private static function test20float(float $data): float { return $data; }
private static function testRet20float($data): float { return $data; }

}