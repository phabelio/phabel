<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;



/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer15Test extends TestCase
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
[fn ($data): string|self => $data, 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[[$this, 'testRet71stringself'], 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[[self::class, 'testRet71stringself'], 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[[$this, 'testRet72stringself'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[[self::class, 'testRet72stringself'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous returned~'],
[fn ($data): ?self => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?self { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet73self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet73self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): ?self => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?self { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet74self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet74self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): self|int => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[$this, 'testRet75selfint'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[self::class, 'testRet75selfint'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~']];
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
[fn (string|self $data): string|self => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[[$this, 'test71stringself'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[[self::class, 'test71stringself'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[[$this, 'test72stringself'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[[self::class, 'test72stringself'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|string, class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test73self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test73self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test74self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test74self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[$this, 'test75selfint'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[self::class, 'test75selfint'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test71stringself(string|self $data): string|self { return $data; }
private static function testRet71stringself($data): string|self { return $data; }
private static function test72stringself(string|self $data): string|self { return $data; }
private static function testRet72stringself($data): string|self { return $data; }
private static function test73self(?self $data): ?self { return $data; }
private static function testRet73self($data): ?self { return $data; }
private static function test74self(?self $data): ?self { return $data; }
private static function testRet74self($data): ?self { return $data; }
private static function test75selfint(self|int $data): self|int { return $data; }
private static function testRet75selfint($data): self|int { return $data; }

}