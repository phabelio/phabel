<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test41bool(?bool $data): ?bool { return $data; }
function testRet41bool($data): ?bool { return $data; }
function test42booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet42booliterable($data): bool|iterable { return $data; }
function test43booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet43booliterable($data): bool|iterable { return $data; }
function test44booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet44booliterable($data): bool|iterable { return $data; }
function test45booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet45booliterable($data): bool|iterable { return $data; }


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
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): ?bool => $data, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet41bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet41bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet41bool', null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet42booliterable'], true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet42booliterable'], true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet42booliterable', true, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet43booliterable'], false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet43booliterable'], false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet43booliterable', false, new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet44booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet44booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet44booliterable', ['lmao'], new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[$this, 'testRet45booliterable'], array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
[[self::class, 'testRet45booliterable'], array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet45booliterable', array(), new class{}, '~.*Return value must be of type iterable\\|bool, class@anonymous returned~']];
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
[fn (?bool $data): ?bool => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test41bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test41bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test41bool', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test42booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test42booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test42booliterable', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test43booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test43booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test43booliterable', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test44booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test44booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test44booliterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[$this, 'test45booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
[[self::class, 'test45booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test45booliterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable\\|bool, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test41bool(?bool $data): ?bool { return $data; }
private static function testRet41bool($data): ?bool { return $data; }
private static function test42booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet42booliterable($data): bool|iterable { return $data; }
private static function test43booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet43booliterable($data): bool|iterable { return $data; }
private static function test44booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet44booliterable($data): bool|iterable { return $data; }
private static function test45booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet45booliterable($data): bool|iterable { return $data; }

}