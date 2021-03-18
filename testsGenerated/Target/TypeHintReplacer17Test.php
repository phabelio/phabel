<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test81booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet81booliterable($data): bool|iterable { return $data; }
function test82booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet82booliterable($data): bool|iterable { return $data; }
function test83booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet83booliterable($data): bool|iterable { return $data; }
function test84booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet84booliterable($data): bool|iterable { return $data; }
function test85booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet85booliterable($data): bool|iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer17Test extends TestCase
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
[fn ($data): bool|iterable => $data, 0, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, 0, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet81booliterable'], 0, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet81booliterable'], 0, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet81booliterable', 0, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, 1, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, 1, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet82booliterable'], 1, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet82booliterable'], 1, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet82booliterable', 1, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, "0", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, "0", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet83booliterable'], "0", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet83booliterable'], "0", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet83booliterable', "0", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, "1", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, "1", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet84booliterable'], "1", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet84booliterable'], "1", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet84booliterable', "1", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, "", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, "", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet85booliterable'], "", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet85booliterable'], "", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet85booliterable', "", new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~']];
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
[fn (bool|iterable $data): bool|iterable => $data, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test81booliterable'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test81booliterable'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test81booliterable', 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test82booliterable'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test82booliterable'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test82booliterable', 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test83booliterable'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test83booliterable'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test83booliterable', "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test84booliterable'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test84booliterable'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test84booliterable', "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test85booliterable'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test85booliterable'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test85booliterable', "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test81booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet81booliterable($data): bool|iterable { return $data; }
private static function test82booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet82booliterable($data): bool|iterable { return $data; }
private static function test83booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet83booliterable($data): bool|iterable { return $data; }
private static function test84booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet84booliterable($data): bool|iterable { return $data; }
private static function test85booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet85booliterable($data): bool|iterable { return $data; }

}