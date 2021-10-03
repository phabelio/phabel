<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test6bool(bool $data): bool { return $data; }
function testRet6bool($data): bool { return $data; }
function test7bool(bool $data): bool { return $data; }
function testRet7bool($data): bool { return $data; }
function test8bool(bool $data): bool { return $data; }
function testRet8bool($data): bool { return $data; }
function test9bool(bool $data): bool { return $data; }
function testRet9bool($data): bool { return $data; }
function test10bool(bool $data): bool { return $data; }
function testRet10bool($data): bool { return $data; }


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
        $this->assertTrue($data == $c($data));

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
[fn ($data): bool => $data, 0, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, 0, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet8bool'], 0, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet8bool'], 0, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet8bool', 0, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, 1, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, 1, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet9bool'], 1, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet9bool'], 1, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet9bool', 1, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, "0", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, "0", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet10bool'], "0", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet10bool'], "0", new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet10bool', "0", new class{}, '~.*Return value must be of type bool, class@anonymous returned~']];
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
[fn (bool $data): bool => $data, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test8bool'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test8bool'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test8bool', 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test9bool'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test9bool'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test9bool', 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test10bool'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test10bool'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test10bool', "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test6bool(bool $data): bool { return $data; }
private static function testRet6bool($data): bool { return $data; }
private static function test7bool(bool $data): bool { return $data; }
private static function testRet7bool($data): bool { return $data; }
private static function test8bool(bool $data): bool { return $data; }
private static function testRet8bool($data): bool { return $data; }
private static function test9bool(bool $data): bool { return $data; }
private static function testRet9bool($data): bool { return $data; }
private static function test10bool(bool $data): bool { return $data; }
private static function testRet10bool($data): bool { return $data; }

}