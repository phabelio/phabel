<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test17int(int $data): int { return $data; }
function testRet17int($data): int { return $data; }
function test18int(int $data): int { return $data; }
function testRet18int($data): int { return $data; }
function test19PhabelTestTargetTypeHintReplacerTest(\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet19PhabelTestTargetTypeHintReplacerTest($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test20Generator(\Generator $data): \Generator { return $data; }
function testRet20Generator($data): \Generator { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer4Test extends TestCase
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
[fn ($data): self => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): self { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet16self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet16self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): int => $data, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet17int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet17int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet17int', 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet18int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet18int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet18int', -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[function ($data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[$this, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[self::class, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet20Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~']];
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
[fn (self $data): self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (self $data): self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test16self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test16self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (int $data): int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test17int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test17int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test17int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test18int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test18int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test18int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (\Generator $data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[function (\Generator $data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[$this, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[self::class, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test20Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test16self(self $data): self { return $data; }
private static function testRet16self($data): self { return $data; }
private static function test17int(int $data): int { return $data; }
private static function testRet17int($data): int { return $data; }
private static function test18int(int $data): int { return $data; }
private static function testRet18int($data): int { return $data; }
private static function test19PhabelTestTargetTypeHintReplacerTest(\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet19PhabelTestTargetTypeHintReplacerTest($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test20Generator(\Generator $data): \Generator { return $data; }
private static function testRet20Generator($data): \Generator { return $data; }

}