<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test26callablearray(callable|array $data): callable|array { return $data; }
function testRet26callablearray($data): callable|array { return $data; }
function test27callablearray(callable|array $data): callable|array { return $data; }
function testRet27callablearray($data): callable|array { return $data; }
function test28callablearray(callable|array $data): callable|array { return $data; }
function testRet28callablearray($data): callable|array { return $data; }
function test29callablearray(callable|array $data): callable|array { return $data; }
function testRet29callablearray($data): callable|array { return $data; }
function test30callablearray(callable|array $data): callable|array { return $data; }
function testRet30callablearray($data): callable|array { return $data; }


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
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): callable|array => $data, "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet26callablearray'], "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet26callablearray'], "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet26callablearray', "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet27callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet27callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet27callablearray', fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet28callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet28callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet28callablearray', [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet29callablearray', [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet30callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet30callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet30callablearray', ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~']];
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
[fn (callable|array $data): callable|array => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test26callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test26callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test26callablearray', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test27callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test27callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test27callablearray', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test28callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test28callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test28callablearray', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test29callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test29callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test29callablearray', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test30callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test30callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test30callablearray', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test26callablearray(callable|array $data): callable|array { return $data; }
private static function testRet26callablearray($data): callable|array { return $data; }
private static function test27callablearray(callable|array $data): callable|array { return $data; }
private static function testRet27callablearray($data): callable|array { return $data; }
private static function test28callablearray(callable|array $data): callable|array { return $data; }
private static function testRet28callablearray($data): callable|array { return $data; }
private static function test29callablearray(callable|array $data): callable|array { return $data; }
private static function testRet29callablearray($data): callable|array { return $data; }
private static function test30callablearray(callable|array $data): callable|array { return $data; }
private static function testRet30callablearray($data): callable|array { return $data; }

}