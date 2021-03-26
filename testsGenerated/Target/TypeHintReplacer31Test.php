<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;



/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer31Test extends TestCase
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
[fn ($data): string|self => $data, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[$this, 'testRet151stringself'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet151stringself'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[$this, 'testRet152stringself'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet152stringself'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[$this, 'testRet153stringself'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet153stringself'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[$this, 'testRet154stringself'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet154stringself'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous returned~'],
[fn ($data): ?self => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous returned~'],
[function ($data): ?self { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous returned~'],
[[$this, 'testRet155self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous returned~'],
[[self::class, 'testRet155self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous returned~']];
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
[fn (string|self $data): string|self => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[$this, 'test151stringself'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test151stringself'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[$this, 'test152stringself'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test152stringself'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[$this, 'test153stringself'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test153stringself'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[$this, 'test154stringself'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test154stringself'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer31Test\\|string, class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous given, .*~'],
[[$this, 'test155self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous given, .*~'],
[[self::class, 'test155self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer31Test, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test151stringself(string|self $data): string|self { return $data; }
private static function testRet151stringself($data): string|self { return $data; }
private static function test152stringself(string|self $data): string|self { return $data; }
private static function testRet152stringself($data): string|self { return $data; }
private static function test153stringself(string|self $data): string|self { return $data; }
private static function testRet153stringself($data): string|self { return $data; }
private static function test154stringself(string|self $data): string|self { return $data; }
private static function testRet154stringself($data): string|self { return $data; }
private static function test155self(?self $data): ?self { return $data; }
private static function testRet155self($data): ?self { return $data; }

}