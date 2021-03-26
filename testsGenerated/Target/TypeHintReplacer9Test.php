<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test41int(int $data): int { return $data; }
function testRet41int($data): int { return $data; }
function test42int(int $data): int { return $data; }
function testRet42int($data): int { return $data; }
function test43int(int $data): int { return $data; }
function testRet43int($data): int { return $data; }
function test44PhabelTestTargetTypeHintReplacer9Test(\PhabelTest\Target\TypeHintReplacer9Test $data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }
function testRet44PhabelTestTargetTypeHintReplacer9Test($data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }
function test45Generator(\Generator $data): \Generator { return $data; }
function testRet45Generator($data): \Generator { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer9Test extends TestCase
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
[fn ($data): int => $data, false, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, false, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet41int'], false, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet41int'], false, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet41int', false, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, '123', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, '123', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet42int'], '123', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet42int'], '123', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet42int', '123', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, '123.0', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, '123.0', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet43int'], '123.0', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet43int'], '123.0', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet43int', '123.0', new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacer9Test => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous returned~'],
[[$this, 'testRet44PhabelTestTargetTypeHintReplacer9Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous returned~'],
[[self::class, 'testRet44PhabelTestTargetTypeHintReplacer9Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous returned~'],
['PhabelTest\Target\testRet44PhabelTestTargetTypeHintReplacer9Test', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous returned~'],
[fn ($data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[function ($data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[$this, 'testRet45Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[self::class, 'testRet45Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet45Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~']];
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
[fn (int $data): int => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test41int'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test41int'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test41int', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test42int'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test42int'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test42int', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test43int'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test43int'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test43int', '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacer9Test $data): \PhabelTest\Target\TypeHintReplacer9Test => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacer9Test $data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous given, .*~'],
[[$this, 'test44PhabelTestTargetTypeHintReplacer9Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous given, .*~'],
[[self::class, 'test44PhabelTestTargetTypeHintReplacer9Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous given, .*~'],
['PhabelTest\Target\test44PhabelTestTargetTypeHintReplacer9Test', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer9Test, class@anonymous given, .*~'],
[fn (\Generator $data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[function (\Generator $data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[$this, 'test45Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[self::class, 'test45Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test45Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test41int(int $data): int { return $data; }
private static function testRet41int($data): int { return $data; }
private static function test42int(int $data): int { return $data; }
private static function testRet42int($data): int { return $data; }
private static function test43int(int $data): int { return $data; }
private static function testRet43int($data): int { return $data; }
private static function test44PhabelTestTargetTypeHintReplacer9Test(\PhabelTest\Target\TypeHintReplacer9Test $data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }
private static function testRet44PhabelTestTargetTypeHintReplacer9Test($data): \PhabelTest\Target\TypeHintReplacer9Test { return $data; }
private static function test45Generator(\Generator $data): \Generator { return $data; }
private static function testRet45Generator($data): \Generator { return $data; }

}