<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test26object(object $data): object { return $data; }
function testRet26object($data): object { return $data; }
function test27string(string $data): string { return $data; }
function testRet27string($data): string { return $data; }
function test28string(string $data): string { return $data; }
function testRet28string($data): string { return $data; }
function test29string(string $data): string { return $data; }
function testRet29string($data): string { return $data; }
function test30string(string $data): string { return $data; }
function testRet30string($data): string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer6Test extends TestCase
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
[fn ($data): object => $data, $this, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, $this, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet26object'], $this, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet26object'], $this, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet26object', $this, 0, '~.*Return value must be of type object, int returned~'],
[fn ($data): string => $data, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet27string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet27string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet27string', 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet28string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet28string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet28string', new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, 123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet29string'], 123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet29string'], 123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet29string', 123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, -1, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, -1, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet30string'], -1, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet30string'], -1, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet30string', -1, new class{}, '~.*Return value must be of type string, class@anonymous returned~']];
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
[fn (object $data): object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test26object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test26object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test26object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[fn (string $data): string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test27string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test27string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test27string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test28string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test28string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test28string', new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test29string'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test29string'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test29string', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test30string'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test30string'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test30string', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test26object(object $data): object { return $data; }
private static function testRet26object($data): object { return $data; }
private static function test27string(string $data): string { return $data; }
private static function testRet27string($data): string { return $data; }
private static function test28string(string $data): string { return $data; }
private static function testRet28string($data): string { return $data; }
private static function test29string(string $data): string { return $data; }
private static function testRet29string($data): string { return $data; }
private static function test30string(string $data): string { return $data; }
private static function testRet30string($data): string { return $data; }

}