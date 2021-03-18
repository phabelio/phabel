<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;



/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer30Test extends TestCase
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
[fn ($data): string|self => $data, 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[$this, 'testRet146stringself'], 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet146stringself'], 'lmao', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[$this, 'testRet147stringself'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet147stringself'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[$this, 'testRet148stringself'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet148stringself'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[$this, 'testRet149stringself'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet149stringself'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[fn ($data): string|self => $data, 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[function ($data): string|self { return $data; }, 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[$this, 'testRet150stringself'], 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~'],
[[self::class, 'testRet150stringself'], 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous returned~']];
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
[fn (string|self $data): string|self => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[$this, 'test146stringself'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test146stringself'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[$this, 'test147stringself'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test147stringself'], new class{public function __toString() { return "lmao"; }}, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[$this, 'test148stringself'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test148stringself'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[$this, 'test149stringself'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test149stringself'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[fn (string|self $data): string|self => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[function (string|self $data): string|self { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[$this, 'test150stringself'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~'],
[[self::class, 'test150stringself'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer30Test\\|string, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test146stringself(string|self $data): string|self { return $data; }
private static function testRet146stringself($data): string|self { return $data; }
private static function test147stringself(string|self $data): string|self { return $data; }
private static function testRet147stringself($data): string|self { return $data; }
private static function test148stringself(string|self $data): string|self { return $data; }
private static function testRet148stringself($data): string|self { return $data; }
private static function test149stringself(string|self $data): string|self { return $data; }
private static function testRet149stringself($data): string|self { return $data; }
private static function test150stringself(string|self $data): string|self { return $data; }
private static function testRet150stringself($data): string|self { return $data; }

}