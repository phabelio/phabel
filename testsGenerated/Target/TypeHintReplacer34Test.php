<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test166int(?int $data): ?int { return $data; }
function testRet166int($data): ?int { return $data; }
function test167int(?int $data): ?int { return $data; }
function testRet167int($data): ?int { return $data; }
function test168int(?int $data): ?int { return $data; }
function testRet168int($data): ?int { return $data; }
function test169int(?int $data): ?int { return $data; }
function testRet169int($data): ?int { return $data; }
function test170int(?int $data): ?int { return $data; }
function testRet170int($data): ?int { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer34Test extends TestCase
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
[fn ($data): ?int => $data, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet166int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet166int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet166int', 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet167int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet167int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet167int', -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, 123.123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, 123.123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet168int'], 123.123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet168int'], 123.123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet168int', 123.123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, 1e3, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, 1e3, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet169int'], 1e3, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet169int'], 1e3, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet169int', 1e3, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, true, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, true, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet170int'], true, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet170int'], true, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet170int', true, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~']];
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
[fn (?int $data): ?int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test166int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test166int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test166int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test167int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test167int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test167int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test168int'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test168int'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test168int', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test169int'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test169int'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test169int', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test170int'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test170int'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test170int', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test166int(?int $data): ?int { return $data; }
private static function testRet166int($data): ?int { return $data; }
private static function test167int(?int $data): ?int { return $data; }
private static function testRet167int($data): ?int { return $data; }
private static function test168int(?int $data): ?int { return $data; }
private static function testRet168int($data): ?int { return $data; }
private static function test169int(?int $data): ?int { return $data; }
private static function testRet169int($data): ?int { return $data; }
private static function test170int(?int $data): ?int { return $data; }
private static function testRet170int($data): ?int { return $data; }

}