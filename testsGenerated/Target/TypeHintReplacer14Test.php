<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test66objectstring(object|string $data): object|string { return $data; }
function testRet66objectstring($data): object|string { return $data; }
function test67objectstring(object|string $data): object|string { return $data; }
function testRet67objectstring($data): object|string { return $data; }
function test68objectstring(object|string $data): object|string { return $data; }
function testRet68objectstring($data): object|string { return $data; }
function test69string(?string $data): ?string { return $data; }
function testRet69string($data): ?string { return $data; }
function test70string(?string $data): ?string { return $data; }
function testRet70string($data): ?string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer14Test extends TestCase
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
[fn ($data): object|string => $data, new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet66objectstring'], new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet66objectstring'], new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet66objectstring', new class{}, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet67objectstring'], $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet67objectstring'], $this, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet67objectstring', $this, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): object|string => $data, 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet68objectstring'], 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet68objectstring'], 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet68objectstring', 'lmao', null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): ?string => $data, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet69string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet69string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet69string', 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet70string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet70string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet70string', null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~']];
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
[fn (object|string $data): object|string => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test66objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test66objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test66objectstring', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test67objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test67objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test67objectstring', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (object|string $data): object|string => $data, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test68objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test68objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test68objectstring', 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (?string $data): ?string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test69string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test69string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test69string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test70string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test70string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test70string', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test66objectstring(object|string $data): object|string { return $data; }
private static function testRet66objectstring($data): object|string { return $data; }
private static function test67objectstring(object|string $data): object|string { return $data; }
private static function testRet67objectstring($data): object|string { return $data; }
private static function test68objectstring(object|string $data): object|string { return $data; }
private static function testRet68objectstring($data): object|string { return $data; }
private static function test69string(?string $data): ?string { return $data; }
private static function testRet69string($data): ?string { return $data; }
private static function test70string(?string $data): ?string { return $data; }
private static function testRet70string($data): ?string { return $data; }

}