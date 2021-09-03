<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test76bool(?bool $data): ?bool { return $data; }
function testRet76bool($data): ?bool { return $data; }
function test77bool(?bool $data): ?bool { return $data; }
function testRet77bool($data): ?bool { return $data; }
function test78bool(?bool $data): ?bool { return $data; }
function testRet78bool($data): ?bool { return $data; }
function test79booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet79booliterable($data): bool|iterable { return $data; }
function test80booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet80booliterable($data): bool|iterable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer16Test extends TestCase
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
[fn ($data): ?bool => $data, "", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, "", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet76bool'], "", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet76bool'], "", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet76bool', "", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, "aaaa", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, "aaaa", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet77bool'], "aaaa", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet77bool'], "aaaa", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet77bool', "aaaa", new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet78bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet78bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet78bool', null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet79booliterable'], true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet79booliterable'], true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet79booliterable', true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet80booliterable'], false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet80booliterable'], false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet80booliterable', false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~']];
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
[fn (?bool $data): ?bool => $data, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test76bool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test76bool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test76bool', "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test77bool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test77bool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test77bool', "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test78bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test78bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test78bool', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test79booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test79booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test79booliterable', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test80booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test80booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test80booliterable', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test76bool(?bool $data): ?bool { return $data; }
private static function testRet76bool($data): ?bool { return $data; }
private static function test77bool(?bool $data): ?bool { return $data; }
private static function testRet77bool($data): ?bool { return $data; }
private static function test78bool(?bool $data): ?bool { return $data; }
private static function testRet78bool($data): ?bool { return $data; }
private static function test79booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet79booliterable($data): bool|iterable { return $data; }
private static function test80booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet80booliterable($data): bool|iterable { return $data; }

}