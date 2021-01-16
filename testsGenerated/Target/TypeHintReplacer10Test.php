<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test46booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet46booliterable($data): bool|iterable { return $data; }
function test47iterable(?iterable $data): ?iterable { return $data; }
function testRet47iterable($data): ?iterable { return $data; }
function test48iterable(?iterable $data): ?iterable { return $data; }
function testRet48iterable($data): ?iterable { return $data; }
function test49iterable(?iterable $data): ?iterable { return $data; }
function testRet49iterable($data): ?iterable { return $data; }
function test50iterable(?iterable $data): ?iterable { return $data; }
function testRet50iterable($data): ?iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer10Test extends TestCase
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
[fn ($data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet46booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): ?iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet47iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet47iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet47iterable', ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet48iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet48iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet48iterable', array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet49iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet50iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet50iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet50iterable', null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~']];
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
[fn (bool|iterable $data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test46booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test47iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test47iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test47iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test48iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test48iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test48iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test49iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test50iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test50iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test50iterable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test46booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet46booliterable($data): bool|iterable { return $data; }
private static function test47iterable(?iterable $data): ?iterable { return $data; }
private static function testRet47iterable($data): ?iterable { return $data; }
private static function test48iterable(?iterable $data): ?iterable { return $data; }
private static function testRet48iterable($data): ?iterable { return $data; }
private static function test49iterable(?iterable $data): ?iterable { return $data; }
private static function testRet49iterable($data): ?iterable { return $data; }
private static function test50iterable(?iterable $data): ?iterable { return $data; }
private static function testRet50iterable($data): ?iterable { return $data; }

}