<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test51iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet51iterablefloat($data): iterable|float { return $data; }
function test52iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet52iterablefloat($data): iterable|float { return $data; }
function test53iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet53iterablefloat($data): iterable|float { return $data; }
function test54iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet54iterablefloat($data): iterable|float { return $data; }
function test55iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet55iterablefloat($data): iterable|float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer11Test extends TestCase
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
[fn ($data): iterable|float => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet51iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet51iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet51iterablefloat', ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet52iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet52iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet52iterablefloat', array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet54iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet54iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet54iterablefloat', 123.123, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet55iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet55iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet55iterablefloat', 1e3, new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~']];
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
[fn (iterable|float $data): iterable|float => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test51iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test51iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test51iterablefloat', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test52iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test52iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test52iterablefloat', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test54iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test54iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test54iterablefloat', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test55iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test55iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test55iterablefloat', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test51iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet51iterablefloat($data): iterable|float { return $data; }
private static function test52iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet52iterablefloat($data): iterable|float { return $data; }
private static function test53iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet53iterablefloat($data): iterable|float { return $data; }
private static function test54iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet54iterablefloat($data): iterable|float { return $data; }
private static function test55iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet55iterablefloat($data): iterable|float { return $data; }

}