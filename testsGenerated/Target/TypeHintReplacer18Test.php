<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test86PhabelTestTargetTypeHintReplacer18TestGenerator(\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
function testRet86PhabelTestTargetTypeHintReplacer18TestGenerator($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
function test87PhabelTestTargetTypeHintReplacer18TestGenerator(\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
function testRet87PhabelTestTargetTypeHintReplacer18TestGenerator($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
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
[fn ($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[[$this, 'testRet86PhabelTestTargetTypeHintReplacer18TestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet86PhabelTestTargetTypeHintReplacer18TestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet86PhabelTestTargetTypeHintReplacer18TestGenerator', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[[$this, 'testRet87PhabelTestTargetTypeHintReplacer18TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet87PhabelTestTargetTypeHintReplacer18TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet87PhabelTestTargetTypeHintReplacer18TestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous returned~'],
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
[fn (\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[[$this, 'test86PhabelTestTargetTypeHintReplacer18TestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test86PhabelTestTargetTypeHintReplacer18TestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test86PhabelTestTargetTypeHintReplacer18TestGenerator', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[[$this, 'test87PhabelTestTargetTypeHintReplacer18TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test87PhabelTestTargetTypeHintReplacer18TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test87PhabelTestTargetTypeHintReplacer18TestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer18Test\\|Generator, class@anonymous given, .*~'],
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
    
    private static function test86PhabelTestTargetTypeHintReplacer18TestGenerator(\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
private static function testRet86PhabelTestTargetTypeHintReplacer18TestGenerator($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
private static function test87PhabelTestTargetTypeHintReplacer18TestGenerator(\PhabelTest\Target\TypeHintReplacer18Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
private static function testRet87PhabelTestTargetTypeHintReplacer18TestGenerator($data): \PhabelTest\Target\TypeHintReplacer18Test|\Generator { return $data; }
private static function test88Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet88Generator($data): ?\Generator { return $data; }
private static function test89Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet89Generator($data): ?\Generator { return $data; }
private static function test90Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet90Generatorcallable($data): \Generator|callable { return $data; }

}