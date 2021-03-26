<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test36int(int $data): int { return $data; }
function testRet36int($data): int { return $data; }
function test37int(int $data): int { return $data; }
function testRet37int($data): int { return $data; }
function test38int(int $data): int { return $data; }
function testRet38int($data): int { return $data; }
function test39int(int $data): int { return $data; }
function testRet39int($data): int { return $data; }
function test40int(int $data): int { return $data; }
function testRet40int($data): int { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer8Test extends TestCase
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
[fn ($data): int => $data, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet36int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet36int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet36int', 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet37int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet37int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet37int', -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, 123.0, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, 123.0, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet38int'], 123.0, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet38int'], 123.0, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet38int', 123.0, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, 1e3, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, 1e3, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet39int'], 1e3, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet39int'], 1e3, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet39int', 1e3, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, true, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, true, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet40int'], true, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet40int'], true, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet40int', true, new class{}, '~.*Return value must be of type int, class@anonymous returned~']];
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
[fn (int $data): int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test36int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test36int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test36int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test37int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test37int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test37int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test38int'], 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test38int'], 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test38int', 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test39int'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test39int'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test39int', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test40int'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test40int'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test40int', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test36int(int $data): int { return $data; }
private static function testRet36int($data): int { return $data; }
private static function test37int(int $data): int { return $data; }
private static function testRet37int($data): int { return $data; }
private static function test38int(int $data): int { return $data; }
private static function testRet38int($data): int { return $data; }
private static function test39int(int $data): int { return $data; }
private static function testRet39int($data): int { return $data; }
private static function test40int(int $data): int { return $data; }
private static function testRet40int($data): int { return $data; }

}