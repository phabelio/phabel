<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test86PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function testRet86PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function test87PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function testRet87PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function test88Generator(?\Generator $data): ?\Generator { return $data; }
function testRet88Generator($data): ?\Generator { return $data; }
function test89Generator(?\Generator $data): ?\Generator { return $data; }
function testRet89Generator($data): ?\Generator { return $data; }
function test90Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet90Generatorcallable($data): \Generator|callable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer18Test extends TestCase
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
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[[$this, 'testRet86PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet86PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet86PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[[$this, 'testRet87PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet87PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet87PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet88Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet88Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet88Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet89Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet89Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet89Generator', null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[[$this, 'testRet90Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[[self::class, 'testRet90Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet90Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~']];
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
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[[$this, 'test86PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test86PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test86PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[[$this, 'test87PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test87PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test87PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test88Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test88Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test88Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test89Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test89Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test89Generator', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[[$this, 'test90Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[[self::class, 'test90Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test90Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test86PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function testRet86PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function test87PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function testRet87PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function test88Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet88Generator($data): ?\Generator { return $data; }
private static function test89Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet89Generator($data): ?\Generator { return $data; }
private static function test90Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet90Generatorcallable($data): \Generator|callable { return $data; }

}