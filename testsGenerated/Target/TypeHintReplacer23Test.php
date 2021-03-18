<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test111float(?float $data): ?float { return $data; }
function testRet111float($data): ?float { return $data; }
function test112float(?float $data): ?float { return $data; }
function testRet112float($data): ?float { return $data; }
function test113float(?float $data): ?float { return $data; }
function testRet113float($data): ?float { return $data; }
function test114floatobject(float|object $data): float|object { return $data; }
function testRet114floatobject($data): float|object { return $data; }
function test115floatobject(float|object $data): float|object { return $data; }
function testRet115floatobject($data): float|object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer23Test extends TestCase
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
[fn ($data): ?float => $data, '123', new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, '123', new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet111float'], '123', new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet111float'], '123', new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet111float', '123', new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, "123.123", new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, "123.123", new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet112float'], "123.123", new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet112float'], "123.123", new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet112float', "123.123", new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet113float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet113float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet113float', null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): float|object => $data, 123, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, 123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet114floatobject'], 123, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet114floatobject'], 123, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet114floatobject', 123, null, '~.*Return value must be of type object\\|float, null returned~'],
[fn ($data): float|object => $data, -1, null, '~.*Return value must be of type object\\|float, null returned~'],
[function ($data): float|object { return $data; }, -1, null, '~.*Return value must be of type object\\|float, null returned~'],
[[$this, 'testRet115floatobject'], -1, null, '~.*Return value must be of type object\\|float, null returned~'],
[[self::class, 'testRet115floatobject'], -1, null, '~.*Return value must be of type object\\|float, null returned~'],
['PhabelTest\Target\testRet115floatobject', -1, null, '~.*Return value must be of type object\\|float, null returned~']];
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
[fn (?float $data): ?float => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test111float'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test111float'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test111float', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test112float'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test112float'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test112float', "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test113float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test113float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test113float', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (float|object $data): float|object => $data, 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test114floatobject'], 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test114floatobject'], 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test114floatobject', 123, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[fn (float|object $data): float|object => $data, -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[$this, 'test115floatobject'], -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
[[self::class, 'test115floatobject'], -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~'],
['PhabelTest\Target\test115floatobject', -1, null, '~.*Argument #1 \\(\\$data\\) must be of type object\\|float, null given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test111float(?float $data): ?float { return $data; }
private static function testRet111float($data): ?float { return $data; }
private static function test112float(?float $data): ?float { return $data; }
private static function testRet112float($data): ?float { return $data; }
private static function test113float(?float $data): ?float { return $data; }
private static function testRet113float($data): ?float { return $data; }
private static function test114floatobject(float|object $data): float|object { return $data; }
private static function testRet114floatobject($data): float|object { return $data; }
private static function test115floatobject(float|object $data): float|object { return $data; }
private static function testRet115floatobject($data): float|object { return $data; }

}