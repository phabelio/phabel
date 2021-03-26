<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;



/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer33Test extends TestCase
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
[fn ($data): self|int => $data, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[$this, 'testRet161selfint'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet161selfint'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[$this, 'testRet162selfint'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet162selfint'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[$this, 'testRet163selfint'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet163selfint'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[$this, 'testRet164selfint'], '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet164selfint'], '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[$this, 'testRet165selfint'], '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet165selfint'], '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous returned~']];
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
[fn (self|int $data): self|int => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[$this, 'test161selfint'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test161selfint'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[$this, 'test162selfint'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test162selfint'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[$this, 'test163selfint'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test163selfint'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[$this, 'test164selfint'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test164selfint'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[$this, 'test165selfint'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test165selfint'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer33Test\\|int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test161selfint(self|int $data): self|int { return $data; }
private static function testRet161selfint($data): self|int { return $data; }
private static function test162selfint(self|int $data): self|int { return $data; }
private static function testRet162selfint($data): self|int { return $data; }
private static function test163selfint(self|int $data): self|int { return $data; }
private static function testRet163selfint($data): self|int { return $data; }
private static function test164selfint(self|int $data): self|int { return $data; }
private static function testRet164selfint($data): self|int { return $data; }
private static function test165selfint(self|int $data): self|int { return $data; }
private static function testRet165selfint($data): self|int { return $data; }

}