<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test91iterable(?iterable $data): ?iterable { return $data; }
function testRet91iterable($data): ?iterable { return $data; }
function test92iterable(?iterable $data): ?iterable { return $data; }
function testRet92iterable($data): ?iterable { return $data; }
function test93iterable(?iterable $data): ?iterable { return $data; }
function testRet93iterable($data): ?iterable { return $data; }
function test94iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet94iterablefloat($data): iterable|float { return $data; }
function test95iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet95iterablefloat($data): iterable|float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer19Test extends TestCase
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
[fn ($data): ?iterable => $data, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet91iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet91iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet91iterable', array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet92iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet92iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet92iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet93iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet93iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet93iterable', null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): iterable|float => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet94iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet94iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet94iterablefloat', ['lmao'], new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[$this, 'testRet95iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
[[self::class, 'testRet95iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~'],
['PhabelTest\Target\testRet95iterablefloat', array(), new class{}, '~.*Return value must be of type iterable\\|float, class@anonymous returned~']];
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
[fn (?iterable $data): ?iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test91iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test91iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test91iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test92iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test92iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test92iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test93iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test93iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test93iterable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test94iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test94iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test94iterablefloat', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[$this, 'test95iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
[[self::class, 'test95iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~'],
['PhabelTest\Target\test95iterablefloat', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test91iterable(?iterable $data): ?iterable { return $data; }
private static function testRet91iterable($data): ?iterable { return $data; }
private static function test92iterable(?iterable $data): ?iterable { return $data; }
private static function testRet92iterable($data): ?iterable { return $data; }
private static function test93iterable(?iterable $data): ?iterable { return $data; }
private static function testRet93iterable($data): ?iterable { return $data; }
private static function test94iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet94iterablefloat($data): iterable|float { return $data; }
private static function test95iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet95iterablefloat($data): iterable|float { return $data; }

}