<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test61arraybool(array|bool $data): array|bool { return $data; }
function testRet61arraybool($data): array|bool { return $data; }
function test62arraybool(array|bool $data): array|bool { return $data; }
function testRet62arraybool($data): array|bool { return $data; }
function test63arraybool(array|bool $data): array|bool { return $data; }
function testRet63arraybool($data): array|bool { return $data; }
function test64arraybool(array|bool $data): array|bool { return $data; }
function testRet64arraybool($data): array|bool { return $data; }
function test65arraybool(array|bool $data): array|bool { return $data; }
function testRet65arraybool($data): array|bool { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer13Test extends TestCase
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
[fn ($data): array|bool => $data, array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet61arraybool'], array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet61arraybool'], array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet61arraybool', array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet62arraybool'], true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet62arraybool'], true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet62arraybool', true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet63arraybool'], false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet63arraybool'], false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet63arraybool', false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, 0, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, 0, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet64arraybool'], 0, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet64arraybool'], 0, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet64arraybool', 0, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, 1, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, 1, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet65arraybool'], 1, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet65arraybool'], 1, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet65arraybool', 1, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~']];
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
[fn (array|bool $data): array|bool => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test61arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test61arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test61arraybool', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test62arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test62arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test62arraybool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test63arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test63arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test63arraybool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test64arraybool'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test64arraybool'], 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test64arraybool', 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test65arraybool'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test65arraybool'], 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test65arraybool', 1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test61arraybool(array|bool $data): array|bool { return $data; }
private static function testRet61arraybool($data): array|bool { return $data; }
private static function test62arraybool(array|bool $data): array|bool { return $data; }
private static function testRet62arraybool($data): array|bool { return $data; }
private static function test63arraybool(array|bool $data): array|bool { return $data; }
private static function testRet63arraybool($data): array|bool { return $data; }
private static function test64arraybool(array|bool $data): array|bool { return $data; }
private static function testRet64arraybool($data): array|bool { return $data; }
private static function test65arraybool(array|bool $data): array|bool { return $data; }
private static function testRet65arraybool($data): array|bool { return $data; }

}