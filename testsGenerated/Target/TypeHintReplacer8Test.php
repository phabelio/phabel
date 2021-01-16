<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test36arraybool(array|bool $data): array|bool { return $data; }
function testRet36arraybool($data): array|bool { return $data; }
function test37arraybool(array|bool $data): array|bool { return $data; }
function testRet37arraybool($data): array|bool { return $data; }
function test38arraybool(array|bool $data): array|bool { return $data; }
function testRet38arraybool($data): array|bool { return $data; }
function test39bool(?bool $data): ?bool { return $data; }
function testRet39bool($data): ?bool { return $data; }
function test40bool(?bool $data): ?bool { return $data; }
function testRet40bool($data): ?bool { return $data; }


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
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): array|bool => $data, array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet36arraybool'], array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet36arraybool'], array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet36arraybool', array(), new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet37arraybool'], true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet37arraybool'], true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet37arraybool', true, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet38arraybool'], false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet38arraybool'], false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet38arraybool', false, new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet39bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet39bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet39bool', true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet40bool'], false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet40bool'], false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet40bool', false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~']];
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
[[$this, 'test36arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test36arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test36arraybool', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test37arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test37arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test37arraybool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test38arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test38arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test38arraybool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test39bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test39bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test39bool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test40bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test40bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test40bool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test36arraybool(array|bool $data): array|bool { return $data; }
private static function testRet36arraybool($data): array|bool { return $data; }
private static function test37arraybool(array|bool $data): array|bool { return $data; }
private static function testRet37arraybool($data): array|bool { return $data; }
private static function test38arraybool(array|bool $data): array|bool { return $data; }
private static function testRet38arraybool($data): array|bool { return $data; }
private static function test39bool(?bool $data): ?bool { return $data; }
private static function testRet39bool($data): ?bool { return $data; }
private static function test40bool(?bool $data): ?bool { return $data; }
private static function testRet40bool($data): ?bool { return $data; }

}