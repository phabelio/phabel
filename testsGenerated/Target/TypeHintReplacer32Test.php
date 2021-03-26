<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;



/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer32Test extends TestCase
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
[fn ($data): ?self => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous returned~'],
[function ($data): ?self { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous returned~'],
[[$this, 'testRet156self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous returned~'],
[[self::class, 'testRet156self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous returned~'],
[fn ($data): self|int => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[$this, 'testRet157selfint'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet157selfint'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[$this, 'testRet158selfint'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet158selfint'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[$this, 'testRet159selfint'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet159selfint'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, 123.0, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, 123.0, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[$this, 'testRet160selfint'], 123.0, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet160selfint'], 123.0, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous returned~']];
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
[fn (?self $data): ?self => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous given, .*~'],
[[$this, 'test156self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous given, .*~'],
[[self::class, 'test156self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer32Test, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[$this, 'test157selfint'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test157selfint'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[$this, 'test158selfint'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test158selfint'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[$this, 'test159selfint'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test159selfint'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[$this, 'test160selfint'], 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test160selfint'], 123.0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer32Test\\|int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test156self(?self $data): ?self { return $data; }
private static function testRet156self($data): ?self { return $data; }
private static function test157selfint(self|int $data): self|int { return $data; }
private static function testRet157selfint($data): self|int { return $data; }
private static function test158selfint(self|int $data): self|int { return $data; }
private static function testRet158selfint($data): self|int { return $data; }
private static function test159selfint(self|int $data): self|int { return $data; }
private static function testRet159selfint($data): self|int { return $data; }
private static function test160selfint(self|int $data): self|int { return $data; }
private static function testRet160selfint($data): self|int { return $data; }

}