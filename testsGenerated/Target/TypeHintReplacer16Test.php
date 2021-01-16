<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test78int(?int $data): ?int { return $data; }
function testRet78int($data): ?int { return $data; }
function test79int(?int $data): ?int { return $data; }
function testRet79int($data): ?int { return $data; }
function test80int(?int $data): ?int { return $data; }
function testRet80int($data): ?int { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer16Test extends TestCase
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
[fn ($data): self|int => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[[$this, 'testRet76selfint'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet76selfint'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[fn ($data): self|int => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[function ($data): self|int { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[[$this, 'testRet77selfint'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet77selfint'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous returned~'],
[fn ($data): ?int => $data, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet78int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet78int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet78int', 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet79int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet79int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet79int', -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet80int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet80int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet80int', null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~']];
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
[fn (self|int $data): self|int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[[$this, 'test76selfint'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test76selfint'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[fn (self|int $data): self|int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[function (self|int $data): self|int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[[$this, 'test77selfint'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test77selfint'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer16Test\\|int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test78int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test78int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test78int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test79int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test79int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test79int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test80int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test80int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test80int', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test76selfint(self|int $data): self|int { return $data; }
private static function testRet76selfint($data): self|int { return $data; }
private static function test77selfint(self|int $data): self|int { return $data; }
private static function testRet77selfint($data): self|int { return $data; }
private static function test78int(?int $data): ?int { return $data; }
private static function testRet78int($data): ?int { return $data; }
private static function test79int(?int $data): ?int { return $data; }
private static function testRet79int($data): ?int { return $data; }
private static function test80int(?int $data): ?int { return $data; }
private static function testRet80int($data): ?int { return $data; }

}