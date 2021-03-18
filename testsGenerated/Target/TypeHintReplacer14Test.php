<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test66arraybool(array|bool $data): array|bool { return $data; }
function testRet66arraybool($data): array|bool { return $data; }
function test67arraybool(array|bool $data): array|bool { return $data; }
function testRet67arraybool($data): array|bool { return $data; }
function test68arraybool(array|bool $data): array|bool { return $data; }
function testRet68arraybool($data): array|bool { return $data; }
function test69arraybool(array|bool $data): array|bool { return $data; }
function testRet69arraybool($data): array|bool { return $data; }
function test70bool(?bool $data): ?bool { return $data; }
function testRet70bool($data): ?bool { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer14Test extends TestCase
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
[fn ($data): array|bool => $data, "0", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, "0", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet66arraybool'], "0", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet66arraybool'], "0", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet66arraybool', "0", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, "1", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, "1", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet67arraybool'], "1", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet67arraybool'], "1", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet67arraybool', "1", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, "", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, "", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet68arraybool'], "", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet68arraybool'], "", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet68arraybool', "", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, "aaaa", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, "aaaa", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[$this, 'testRet69arraybool'], "aaaa", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[[self::class, 'testRet69arraybool'], "aaaa", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet69arraybool', "aaaa", new class{}, '~.*Return value must be of type array\\|bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet70bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet70bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet70bool', true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~']];
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
[fn (array|bool $data): array|bool => $data, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test66arraybool'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test66arraybool'], "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test66arraybool', "0", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test67arraybool'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test67arraybool'], "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test67arraybool', "1", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test68arraybool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test68arraybool'], "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test68arraybool', "", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[$this, 'test69arraybool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[[self::class, 'test69arraybool'], "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test69arraybool', "aaaa", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array\\|bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test70bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test70bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test70bool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test66arraybool(array|bool $data): array|bool { return $data; }
private static function testRet66arraybool($data): array|bool { return $data; }
private static function test67arraybool(array|bool $data): array|bool { return $data; }
private static function testRet67arraybool($data): array|bool { return $data; }
private static function test68arraybool(array|bool $data): array|bool { return $data; }
private static function testRet68arraybool($data): array|bool { return $data; }
private static function test69arraybool(array|bool $data): array|bool { return $data; }
private static function testRet69arraybool($data): array|bool { return $data; }
private static function test70bool(?bool $data): ?bool { return $data; }
private static function testRet70bool($data): ?bool { return $data; }

}