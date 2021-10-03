<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test101iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet101iterablefloat($data): iterable|float { return $data; }
function test102iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet102iterablefloat($data): iterable|float { return $data; }
function test103iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet103iterablefloat($data): iterable|float { return $data; }
function test104iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet104iterablefloat($data): iterable|float { return $data; }
function test105float(?float $data): ?float { return $data; }
function testRet105float($data): ?float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer21Test extends TestCase
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
[fn ($data): iterable|float => $data, true, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, true, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet101iterablefloat'], true, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet101iterablefloat'], true, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet101iterablefloat', true, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, false, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, false, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet102iterablefloat'], false, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet102iterablefloat'], false, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet102iterablefloat', false, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, '123', new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, '123', new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet103iterablefloat'], '123', new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet103iterablefloat'], '123', new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet103iterablefloat', '123', new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, "123.123", new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, "123.123", new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet104iterablefloat'], "123.123", new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet104iterablefloat'], "123.123", new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet104iterablefloat', "123.123", new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): ?float => $data, 123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet105float'], 123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet105float'], 123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet105float', 123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~']];
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
[fn (iterable|float $data): iterable|float => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test101iterablefloat'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test101iterablefloat'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test101iterablefloat', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test102iterablefloat'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test102iterablefloat'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test102iterablefloat', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test103iterablefloat'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test103iterablefloat'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test103iterablefloat', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test104iterablefloat'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test104iterablefloat'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test104iterablefloat', "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test105float'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test105float'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test105float', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test101iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet101iterablefloat($data): iterable|float { return $data; }
private static function test102iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet102iterablefloat($data): iterable|float { return $data; }
private static function test103iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet103iterablefloat($data): iterable|float { return $data; }
private static function test104iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet104iterablefloat($data): iterable|float { return $data; }
private static function test105float(?float $data): ?float { return $data; }
private static function testRet105float($data): ?float { return $data; }

}