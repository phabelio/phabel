<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test31callablearray(callable|array $data): callable|array { return $data; }
function testRet31callablearray($data): callable|array { return $data; }
function test32array(?array $data): ?array { return $data; }
function testRet32array($data): ?array { return $data; }
function test33array(?array $data): ?array { return $data; }
function testRet33array($data): ?array { return $data; }
function test34array(?array $data): ?array { return $data; }
function testRet34array($data): ?array { return $data; }
function test35arraybool(array|bool $data): array|bool { return $data; }
function testRet35arraybool($data): array|bool { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer7Test extends TestCase
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
[fn ($data): callable|array => $data, array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[$this, 'testRet31callablearray'], array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet31callablearray'], array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet31callablearray', array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): ?array => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet32array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet32array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet32array', ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet33array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet33array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet33array', array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet34array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet34array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet34array', null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): array|bool => $data, ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet35arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet35arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet35arraybool', ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~']];
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
[fn (callable|array $data): callable|array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[$this, 'test31callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test31callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test31callablearray', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test32array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test32array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test32array', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test33array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test33array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test33array', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test34array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test34array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test34array', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test35arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test35arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test35arraybool', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test31callablearray(callable|array $data): callable|array { return $data; }
private static function testRet31callablearray($data): callable|array { return $data; }
private static function test32array(?array $data): ?array { return $data; }
private static function testRet32array($data): ?array { return $data; }
private static function test33array(?array $data): ?array { return $data; }
private static function testRet33array($data): ?array { return $data; }
private static function test34array(?array $data): ?array { return $data; }
private static function testRet34array($data): ?array { return $data; }
private static function test35arraybool(array|bool $data): array|bool { return $data; }
private static function testRet35arraybool($data): array|bool { return $data; }

}