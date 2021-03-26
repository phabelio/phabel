<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test86booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet86booliterable($data): bool|iterable { return $data; }
function test87booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet87booliterable($data): bool|iterable { return $data; }
function test88booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet88booliterable($data): bool|iterable { return $data; }
function test89booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet89booliterable($data): bool|iterable { return $data; }
function test90iterable(?iterable $data): ?iterable { return $data; }
function testRet90iterable($data): ?iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer18Test extends TestCase
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
[fn ($data): bool|iterable => $data, "aaaa", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, "aaaa", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet86booliterable'], "aaaa", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet86booliterable'], "aaaa", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet86booliterable', "aaaa", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet87booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet87booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet87booliterable', ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet88booliterable'], array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet88booliterable'], array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet88booliterable', array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet89booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet89booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet89booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): ?iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet90iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet90iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet90iterable', ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~']];
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
[fn (bool|iterable $data): bool|iterable => $data, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test86booliterable'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test86booliterable'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test86booliterable', "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test87booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test87booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test87booliterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test88booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test88booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test88booliterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test89booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test89booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test89booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test90iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test90iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test90iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test86booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet86booliterable($data): bool|iterable { return $data; }
private static function test87booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet87booliterable($data): bool|iterable { return $data; }
private static function test88booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet88booliterable($data): bool|iterable { return $data; }
private static function test89booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet89booliterable($data): bool|iterable { return $data; }
private static function test90iterable(?iterable $data): ?iterable { return $data; }
private static function testRet90iterable($data): ?iterable { return $data; }

}