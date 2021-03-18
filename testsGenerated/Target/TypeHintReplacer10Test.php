<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test46callable(?callable $data): ?callable { return $data; }
function testRet46callable($data): ?callable { return $data; }
function test47callable(?callable $data): ?callable { return $data; }
function testRet47callable($data): ?callable { return $data; }
function test48callable(?callable $data): ?callable { return $data; }
function testRet48callable($data): ?callable { return $data; }
function test49callable(?callable $data): ?callable { return $data; }
function testRet49callable($data): ?callable { return $data; }
function test50callable(?callable $data): ?callable { return $data; }
function testRet50callable($data): ?callable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer10Test extends TestCase
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
[fn ($data): ?callable => $data, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet46callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet46callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet46callable', "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet47callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet47callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet47callable', fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet48callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet48callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet48callable', [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet49callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet49callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet49callable', [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet50callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet50callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet50callable', null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~']];
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
[fn (?callable $data): ?callable => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test46callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test46callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test46callable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test47callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test47callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test47callable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test48callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test48callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test48callable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test49callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test49callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test49callable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test50callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test50callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test50callable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test46callable(?callable $data): ?callable { return $data; }
private static function testRet46callable($data): ?callable { return $data; }
private static function test47callable(?callable $data): ?callable { return $data; }
private static function testRet47callable($data): ?callable { return $data; }
private static function test48callable(?callable $data): ?callable { return $data; }
private static function testRet48callable($data): ?callable { return $data; }
private static function test49callable(?callable $data): ?callable { return $data; }
private static function testRet49callable($data): ?callable { return $data; }
private static function test50callable(?callable $data): ?callable { return $data; }
private static function testRet50callable($data): ?callable { return $data; }

}