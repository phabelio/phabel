<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test51callablearray(callable|array $data): callable|array { return $data; }
function testRet51callablearray($data): callable|array { return $data; }
function test52callablearray(callable|array $data): callable|array { return $data; }
function testRet52callablearray($data): callable|array { return $data; }
function test53callablearray(callable|array $data): callable|array { return $data; }
function testRet53callablearray($data): callable|array { return $data; }
function test54callablearray(callable|array $data): callable|array { return $data; }
function testRet54callablearray($data): callable|array { return $data; }
function test55callablearray(callable|array $data): callable|array { return $data; }
function testRet55callablearray($data): callable|array { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer11Test extends TestCase
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
[fn ($data): callable|array => $data, "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet51callablearray'], "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet51callablearray'], "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet51callablearray', "is_null", new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet52callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet52callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet52callablearray', fn (): int => 0, new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet53callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet53callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet53callablearray', [$this, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet54callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet54callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet54callablearray', [self::class, "noop"], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet55callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet55callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet55callablearray', ['lmao'], new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~']];
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
[fn (callable|array $data): callable|array => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test51callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test51callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test51callablearray', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test52callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test52callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test52callablearray', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test53callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test53callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test53callablearray', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test54callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test54callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test54callablearray', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test55callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test55callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test55callablearray', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test51callablearray(callable|array $data): callable|array { return $data; }
private static function testRet51callablearray($data): callable|array { return $data; }
private static function test52callablearray(callable|array $data): callable|array { return $data; }
private static function testRet52callablearray($data): callable|array { return $data; }
private static function test53callablearray(callable|array $data): callable|array { return $data; }
private static function testRet53callablearray($data): callable|array { return $data; }
private static function test54callablearray(callable|array $data): callable|array { return $data; }
private static function testRet54callablearray($data): callable|array { return $data; }
private static function test55callablearray(callable|array $data): callable|array { return $data; }
private static function testRet55callablearray($data): callable|array { return $data; }

}