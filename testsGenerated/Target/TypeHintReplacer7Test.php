<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test31string(string $data): string { return $data; }
function testRet31string($data): string { return $data; }
function test32string(string $data): string { return $data; }
function testRet32string($data): string { return $data; }
function test33string(string $data): string { return $data; }
function testRet33string($data): string { return $data; }
function test34string(string $data): string { return $data; }
function testRet34string($data): string { return $data; }


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
[fn ($data): string => $data, 123.123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 123.123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet31string'], 123.123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet31string'], 123.123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet31string', 123.123, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, 1e3, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 1e3, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet32string'], 1e3, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet32string'], 1e3, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet32string', 1e3, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, true, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, true, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet33string'], true, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet33string'], true, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet33string', true, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): string => $data, false, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, false, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet34string'], false, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet34string'], false, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet34string', false, new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): self => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous returned~'],
[function ($data): self { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous returned~'],
[[$this, 'testRet35self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous returned~'],
[[self::class, 'testRet35self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous returned~']];
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
[fn (string $data): string => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test31string'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test31string'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test31string', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test32string'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test32string'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test32string', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test33string'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test33string'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test33string', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (string $data): string => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test34string'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test34string'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test34string', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (self $data): self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous given, .*~'],
[function (self $data): self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous given, .*~'],
[[$this, 'test35self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous given, .*~'],
[[self::class, 'test35self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer7Test, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test31string(string $data): string { return $data; }
private static function testRet31string($data): string { return $data; }
private static function test32string(string $data): string { return $data; }
private static function testRet32string($data): string { return $data; }
private static function test33string(string $data): string { return $data; }
private static function testRet33string($data): string { return $data; }
private static function test34string(string $data): string { return $data; }
private static function testRet34string($data): string { return $data; }
private static function test35self(self $data): self { return $data; }
private static function testRet35self($data): self { return $data; }

}