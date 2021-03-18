<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test106float(?float $data): ?float { return $data; }
function testRet106float($data): ?float { return $data; }
function test107float(?float $data): ?float { return $data; }
function testRet107float($data): ?float { return $data; }
function test108float(?float $data): ?float { return $data; }
function testRet108float($data): ?float { return $data; }
function test109float(?float $data): ?float { return $data; }
function testRet109float($data): ?float { return $data; }
function test110float(?float $data): ?float { return $data; }
function testRet110float($data): ?float { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer22Test extends TestCase
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
[fn ($data): ?float => $data, -1, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, -1, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet106float'], -1, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet106float'], -1, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet106float', -1, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet107float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet107float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet107float', 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet108float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet108float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet108float', 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, true, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, true, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet109float'], true, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet109float'], true, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet109float', true, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, false, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, false, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet110float'], false, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet110float'], false, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet110float', false, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~']];
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
[fn (?float $data): ?float => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test106float'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test106float'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test106float', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test107float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test107float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test107float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test108float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test108float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test108float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test109float'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test109float'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test109float', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test110float'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test110float'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test110float', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test106float(?float $data): ?float { return $data; }
private static function testRet106float($data): ?float { return $data; }
private static function test107float(?float $data): ?float { return $data; }
private static function testRet107float($data): ?float { return $data; }
private static function test108float(?float $data): ?float { return $data; }
private static function testRet108float($data): ?float { return $data; }
private static function test109float(?float $data): ?float { return $data; }
private static function testRet109float($data): ?float { return $data; }
private static function test110float(?float $data): ?float { return $data; }
private static function testRet110float($data): ?float { return $data; }

}