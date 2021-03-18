<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test136objectstring(object|string $data): object|string { return $data; }
function testRet136objectstring($data): object|string { return $data; }
function test137string(?string $data): ?string { return $data; }
function testRet137string($data): ?string { return $data; }
function test138string(?string $data): ?string { return $data; }
function testRet138string($data): ?string { return $data; }
function test139string(?string $data): ?string { return $data; }
function testRet139string($data): ?string { return $data; }
function test140string(?string $data): ?string { return $data; }
function testRet140string($data): ?string { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer28Test extends TestCase
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
[fn ($data): object|string => $data, false, null, '~.*Return value must be of type object\\|string, null returned~'],
[function ($data): object|string { return $data; }, false, null, '~.*Return value must be of type object\\|string, null returned~'],
[[$this, 'testRet136objectstring'], false, null, '~.*Return value must be of type object\\|string, null returned~'],
[[self::class, 'testRet136objectstring'], false, null, '~.*Return value must be of type object\\|string, null returned~'],
['PhabelTest\Target\testRet136objectstring', false, null, '~.*Return value must be of type object\\|string, null returned~'],
[fn ($data): ?string => $data, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet137string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet137string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet137string', 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet138string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet138string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet138string', new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, 123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet139string'], 123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet139string'], 123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet139string', 123, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, -1, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, -1, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet140string'], -1, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet140string'], -1, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet140string', -1, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~']];
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
[fn (object|string $data): object|string => $data, false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[$this, 'test136objectstring'], false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[[self::class, 'test136objectstring'], false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
['PhabelTest\Target\test136objectstring', false, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|string, null given, .*~'],
[fn (?string $data): ?string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test137string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test137string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test137string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test138string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test138string'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test138string', new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test139string'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test139string'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test139string', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test140string'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test140string'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test140string', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test136objectstring(object|string $data): object|string { return $data; }
private static function testRet136objectstring($data): object|string { return $data; }
private static function test137string(?string $data): ?string { return $data; }
private static function testRet137string($data): ?string { return $data; }
private static function test138string(?string $data): ?string { return $data; }
private static function testRet138string($data): ?string { return $data; }
private static function test139string(?string $data): ?string { return $data; }
private static function testRet139string($data): ?string { return $data; }
private static function test140string(?string $data): ?string { return $data; }
private static function testRet140string($data): ?string { return $data; }

}