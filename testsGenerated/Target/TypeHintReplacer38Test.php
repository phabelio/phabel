<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test186PhabelTestTargetTypeHintReplacer38TestGenerator(\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
function testRet186PhabelTestTargetTypeHintReplacer38TestGenerator($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
function test187PhabelTestTargetTypeHintReplacer38TestGenerator(\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
function testRet187PhabelTestTargetTypeHintReplacer38TestGenerator($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
function test188Generator(?\Generator $data): ?\Generator { return $data; }
function testRet188Generator($data): ?\Generator { return $data; }
function test189Generator(?\Generator $data): ?\Generator { return $data; }
function testRet189Generator($data): ?\Generator { return $data; }
function test190Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet190Generatorcallable($data): \Generator|callable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer38Test extends TestCase
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
[fn ($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[[$this, 'testRet186PhabelTestTargetTypeHintReplacer38TestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet186PhabelTestTargetTypeHintReplacer38TestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet186PhabelTestTargetTypeHintReplacer38TestGenerator', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[[$this, 'testRet187PhabelTestTargetTypeHintReplacer38TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[[self::class, 'testRet187PhabelTestTargetTypeHintReplacer38TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet187PhabelTestTargetTypeHintReplacer38TestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet188Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet188Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet188Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet189Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet189Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet189Generator', null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[[$this, 'testRet190Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
[[self::class, 'testRet190Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet190Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator\\|callable, class@anonymous returned~']];
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
[fn (\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[[$this, 'test186PhabelTestTargetTypeHintReplacer38TestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test186PhabelTestTargetTypeHintReplacer38TestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test186PhabelTestTargetTypeHintReplacer38TestGenerator', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[[$this, 'test187PhabelTestTargetTypeHintReplacer38TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[[self::class, 'test187PhabelTestTargetTypeHintReplacer38TestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test187PhabelTestTargetTypeHintReplacer38TestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer38Test\\|Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test188Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test188Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test188Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test189Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test189Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test189Generator', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[[$this, 'test190Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
[[self::class, 'test190Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test190Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator\\|callable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test186PhabelTestTargetTypeHintReplacer38TestGenerator(\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
private static function testRet186PhabelTestTargetTypeHintReplacer38TestGenerator($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
private static function test187PhabelTestTargetTypeHintReplacer38TestGenerator(\PhabelTest\Target\TypeHintReplacer38Test|\Generator $data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
private static function testRet187PhabelTestTargetTypeHintReplacer38TestGenerator($data): \PhabelTest\Target\TypeHintReplacer38Test|\Generator { return $data; }
private static function test188Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet188Generator($data): ?\Generator { return $data; }
private static function test189Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet189Generator($data): ?\Generator { return $data; }
private static function test190Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet190Generatorcallable($data): \Generator|callable { return $data; }

}