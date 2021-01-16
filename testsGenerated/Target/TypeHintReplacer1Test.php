<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test1callable(callable $data): callable { return $data; }
function testRet1callable($data): callable { return $data; }
function test2callable(callable $data): callable { return $data; }
function testRet2callable($data): callable { return $data; }
function test3callable(callable $data): callable { return $data; }
function testRet3callable($data): callable { return $data; }
function test4array(array $data): array { return $data; }
function testRet4array($data): array { return $data; }
function test5array(array $data): array { return $data; }
function testRet5array($data): array { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer1Test extends TestCase
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
[fn ($data): callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet1callable'], fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet1callable'], fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet1callable', fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet2callable'], [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet2callable'], [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet2callable', [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet3callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet3callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet3callable', [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): array => $data, ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[function ($data): array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[$this, 'testRet4array'], ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[self::class, 'testRet4array'], ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
['PhabelTest\Target\testRet4array', ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[fn ($data): array => $data, array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[function ($data): array { return $data; }, array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[$this, 'testRet5array'], array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[self::class, 'testRet5array'], array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
['PhabelTest\Target\testRet5array', array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~']];
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
[fn (callable $data): callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test1callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test1callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test1callable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (callable $data): callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test2callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test2callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test2callable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (callable $data): callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test3callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test3callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test3callable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (array $data): array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[function (array $data): array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[$this, 'test4array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[self::class, 'test4array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
['PhabelTest\Target\test4array', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[fn (array $data): array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[function (array $data): array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[$this, 'test5array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[self::class, 'test5array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
['PhabelTest\Target\test5array', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test1callable(callable $data): callable { return $data; }
private static function testRet1callable($data): callable { return $data; }
private static function test2callable(callable $data): callable { return $data; }
private static function testRet2callable($data): callable { return $data; }
private static function test3callable(callable $data): callable { return $data; }
private static function testRet3callable($data): callable { return $data; }
private static function test4array(array $data): array { return $data; }
private static function testRet4array($data): array { return $data; }
private static function test5array(array $data): array { return $data; }
private static function testRet5array($data): array { return $data; }

}