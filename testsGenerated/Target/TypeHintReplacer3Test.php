<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test11bool(bool $data): bool { return $data; }
function testRet11bool($data): bool { return $data; }
function test12bool(bool $data): bool { return $data; }
function testRet12bool($data): bool { return $data; }
function test13bool(bool $data): bool { return $data; }
function testRet13bool($data): bool { return $data; }
function test14iterable(iterable $data): iterable { return $data; }
function testRet14iterable($data): iterable { return $data; }
function test15iterable(iterable $data): iterable { return $data; }
function testRet15iterable($data): iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer3Test extends TestCase
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
[fn ($data): bool => $data, "1", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, "1", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet11bool'], "1", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet11bool'], "1", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet11bool', "1", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, "", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, "", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet12bool'], "", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet12bool'], "", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet12bool', "", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, "aaaa", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, "aaaa", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet13bool'], "aaaa", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet13bool'], "aaaa", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet13bool', "aaaa", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet14iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet14iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet14iterable', ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): iterable => $data, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet15iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet15iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet15iterable', array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~']];
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
[fn (bool $data): bool => $data, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test11bool'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test11bool'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test11bool', "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test12bool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test12bool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test12bool', "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test13bool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test13bool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test13bool', "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test14iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test14iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test14iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test15iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test15iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test15iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test11bool(bool $data): bool { return $data; }
private static function testRet11bool($data): bool { return $data; }
private static function test12bool(bool $data): bool { return $data; }
private static function testRet12bool($data): bool { return $data; }
private static function test13bool(bool $data): bool { return $data; }
private static function testRet13bool($data): bool { return $data; }
private static function test14iterable(iterable $data): iterable { return $data; }
private static function testRet14iterable($data): iterable { return $data; }
private static function test15iterable(iterable $data): iterable { return $data; }
private static function testRet15iterable($data): iterable { return $data; }

}