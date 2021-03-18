<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test96iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet96iterablefloat($data): iterable|float { return $data; }
function test97iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet97iterablefloat($data): iterable|float { return $data; }
function test98iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet98iterablefloat($data): iterable|float { return $data; }
function test99iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet99iterablefloat($data): iterable|float { return $data; }
function test100iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet100iterablefloat($data): iterable|float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer20Test extends TestCase
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
[fn ($data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet96iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet96iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet96iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet97iterablefloat'], 123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet97iterablefloat'], 123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet97iterablefloat', 123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, -1, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, -1, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet98iterablefloat'], -1, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet98iterablefloat'], -1, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet98iterablefloat', -1, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet99iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet99iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet99iterablefloat', 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet100iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet100iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet100iterablefloat', 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~']];
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
[fn (iterable|float $data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test96iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test96iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test96iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test97iterablefloat'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test97iterablefloat'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test97iterablefloat', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test98iterablefloat'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test98iterablefloat'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test98iterablefloat', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test99iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test99iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test99iterablefloat', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test100iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test100iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test100iterablefloat', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test96iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet96iterablefloat($data): iterable|float { return $data; }
private static function test97iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet97iterablefloat($data): iterable|float { return $data; }
private static function test98iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet98iterablefloat($data): iterable|float { return $data; }
private static function test99iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet99iterablefloat($data): iterable|float { return $data; }
private static function test100iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet100iterablefloat($data): iterable|float { return $data; }

}