<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test56callablearray(callable|array $data): callable|array { return $data; }
function testRet56callablearray($data): callable|array { return $data; }
function test57array(?array $data): ?array { return $data; }
function testRet57array($data): ?array { return $data; }
function test58array(?array $data): ?array { return $data; }
function testRet58array($data): ?array { return $data; }
function test59array(?array $data): ?array { return $data; }
function testRet59array($data): ?array { return $data; }
function test60arraybool(array|bool $data): array|bool { return $data; }
function testRet60arraybool($data): array|bool { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer12Test extends TestCase
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
[[$this, 'testRet56callablearray'], array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[[self::class, 'testRet56callablearray'], array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
['PhabelTest\Target\testRet56callablearray', array(), new class{}, '~.*Return value must be of type callable\\|array, class@anonymous returned~'],
[fn ($data): ?array => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet57array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet57array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet57array', ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet58array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet58array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet58array', array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet59array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet59array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet59array', null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): array|bool => $data, ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet60arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet60arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet60arraybool', ['lmao'], new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~']];
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
[[$this, 'test56callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[[self::class, 'test56callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
['PhabelTest\Target\test56callablearray', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable\\|array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test57array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test57array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test57array', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test58array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test58array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test58array', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test59array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test59array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test59array', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test60arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test60arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test60arraybool', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test56callablearray(callable|array $data): callable|array { return $data; }
private static function testRet56callablearray($data): callable|array { return $data; }
private static function test57array(?array $data): ?array { return $data; }
private static function testRet57array($data): ?array { return $data; }
private static function test58array(?array $data): ?array { return $data; }
private static function testRet58array($data): ?array { return $data; }
private static function test59array(?array $data): ?array { return $data; }
private static function testRet59array($data): ?array { return $data; }
private static function test60arraybool(array|bool $data): array|bool { return $data; }
private static function testRet60arraybool($data): array|bool { return $data; }

}