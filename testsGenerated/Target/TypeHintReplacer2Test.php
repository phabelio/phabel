<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test6bool(bool $data): bool { return $data; }
function testRet6bool($data): bool { return $data; }
function test7bool(bool $data): bool { return $data; }
function testRet7bool($data): bool { return $data; }
function test8iterable(iterable $data): iterable { return $data; }
function testRet8iterable($data): iterable { return $data; }
function test9iterable(iterable $data): iterable { return $data; }
function testRet9iterable($data): iterable { return $data; }
function test10iterable(iterable $data): iterable { return $data; }
function testRet10iterable($data): iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer2Test extends TestCase
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
[fn ($data): bool => $data, true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet6bool'], true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet6bool'], true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet6bool', true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet7bool'], false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet7bool'], false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet7bool', false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet8iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet8iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet8iterable', ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): iterable => $data, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet9iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet9iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet9iterable', array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet10iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~']];
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
[fn (bool $data): bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test6bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test6bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test6bool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test7bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test7bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test7bool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test8iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test8iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test8iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test9iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test9iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test9iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test10iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test6bool(bool $data): bool { return $data; }
private static function testRet6bool($data): bool { return $data; }
private static function test7bool(bool $data): bool { return $data; }
private static function testRet7bool($data): bool { return $data; }
private static function test8iterable(iterable $data): iterable { return $data; }
private static function testRet8iterable($data): iterable { return $data; }
private static function test9iterable(iterable $data): iterable { return $data; }
private static function testRet9iterable($data): iterable { return $data; }
private static function test10iterable(iterable $data): iterable { return $data; }
private static function testRet10iterable($data): iterable { return $data; }

}