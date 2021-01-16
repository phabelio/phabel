<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test21callable(?callable $data): ?callable { return $data; }
function testRet21callable($data): ?callable { return $data; }
function test22callable(?callable $data): ?callable { return $data; }
function testRet22callable($data): ?callable { return $data; }
function test23callable(?callable $data): ?callable { return $data; }
function testRet23callable($data): ?callable { return $data; }
function test24callable(?callable $data): ?callable { return $data; }
function testRet24callable($data): ?callable { return $data; }
function test25callable(?callable $data): ?callable { return $data; }
function testRet25callable($data): ?callable { return $data; }


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
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): ?callable => $data, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet21callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet21callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet21callable', "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet22callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet22callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet22callable', fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet23callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet23callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet23callable', [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet24callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet24callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet24callable', [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet25callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet25callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet25callable', null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~']];
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
[[$this, 'test21callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test21callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test21callable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test22callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test22callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test22callable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test23callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test23callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test23callable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test24callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test24callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test24callable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test25callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test25callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test25callable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test21callable(?callable $data): ?callable { return $data; }
private static function testRet21callable($data): ?callable { return $data; }
private static function test22callable(?callable $data): ?callable { return $data; }
private static function testRet22callable($data): ?callable { return $data; }
private static function test23callable(?callable $data): ?callable { return $data; }
private static function testRet23callable($data): ?callable { return $data; }
private static function test24callable(?callable $data): ?callable { return $data; }
private static function testRet24callable($data): ?callable { return $data; }
private static function test25callable(?callable $data): ?callable { return $data; }
private static function testRet25callable($data): ?callable { return $data; }

}