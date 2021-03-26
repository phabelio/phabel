<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test21float(float $data): float { return $data; }
function testRet21float($data): float { return $data; }
function test22float(float $data): float { return $data; }
function testRet22float($data): float { return $data; }
function test23float(float $data): float { return $data; }
function testRet23float($data): float { return $data; }
function test24float(float $data): float { return $data; }
function testRet24float($data): float { return $data; }
function test25object(object $data): object { return $data; }
function testRet25object($data): object { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer5Test extends TestCase
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
[fn ($data): float => $data, true, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, true, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet21float'], true, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet21float'], true, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet21float', true, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, false, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, false, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet22float'], false, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet22float'], false, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet22float', false, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, '123', new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, '123', new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet23float'], '123', new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet23float'], '123', new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet23float', '123', new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, "123.123", new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, "123.123", new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet24float'], "123.123", new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet24float'], "123.123", new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet24float', "123.123", new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): object => $data, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet25object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet25object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet25object', new class{}, 0, '~.*Return value must be of type object, int returned~']];
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
[fn (float $data): float => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test21float'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test21float'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test21float', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test22float'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test22float'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test22float', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test23float'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test23float'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test23float', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test24float'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test24float'], "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test24float', "123.123", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (object $data): object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test25object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test25object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test25object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test21float(float $data): float { return $data; }
private static function testRet21float($data): float { return $data; }
private static function test22float(float $data): float { return $data; }
private static function testRet22float($data): float { return $data; }
private static function test23float(float $data): float { return $data; }
private static function testRet23float($data): float { return $data; }
private static function test24float(float $data): float { return $data; }
private static function testRet24float($data): float { return $data; }
private static function test25object(object $data): object { return $data; }
private static function testRet25object($data): object { return $data; }

}