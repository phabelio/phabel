<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test171int(?int $data): ?int { return $data; }
function testRet171int($data): ?int { return $data; }
function test172int(?int $data): ?int { return $data; }
function testRet172int($data): ?int { return $data; }
function test173int(?int $data): ?int { return $data; }
function testRet173int($data): ?int { return $data; }
function test174int(?int $data): ?int { return $data; }
function testRet174int($data): ?int { return $data; }
function test175intPhabelTestTargetTypeHintReplacer35Test(int|\PhabelTest\Target\TypeHintReplacer35Test $data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }
function testRet175intPhabelTestTargetTypeHintReplacer35Test($data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer35Test extends TestCase
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
[fn ($data): ?int => $data, false, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, false, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet171int'], false, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet171int'], false, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet171int', false, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, '123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, '123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet172int'], '123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet172int'], '123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet172int', '123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, '123.123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, '123.123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet173int'], '123.123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet173int'], '123.123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet173int', '123.123', new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet174int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet174int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet174int', null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer35Test => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous returned~'],
[[$this, 'testRet175intPhabelTestTargetTypeHintReplacer35Test'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet175intPhabelTestTargetTypeHintReplacer35Test'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet175intPhabelTestTargetTypeHintReplacer35Test', 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous returned~']];
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
[fn (?int $data): ?int => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test171int'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test171int'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test171int', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test172int'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test172int'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test172int', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, '123.123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, '123.123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test173int'], '123.123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test173int'], '123.123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test173int', '123.123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test174int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test174int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test174int', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer35Test $data): int|\PhabelTest\Target\TypeHintReplacer35Test => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer35Test $data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous given, .*~'],
[[$this, 'test175intPhabelTestTargetTypeHintReplacer35Test'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test175intPhabelTestTargetTypeHintReplacer35Test'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test175intPhabelTestTargetTypeHintReplacer35Test', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer35Test\\|int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test171int(?int $data): ?int { return $data; }
private static function testRet171int($data): ?int { return $data; }
private static function test172int(?int $data): ?int { return $data; }
private static function testRet172int($data): ?int { return $data; }
private static function test173int(?int $data): ?int { return $data; }
private static function testRet173int($data): ?int { return $data; }
private static function test174int(?int $data): ?int { return $data; }
private static function testRet174int($data): ?int { return $data; }
private static function test175intPhabelTestTargetTypeHintReplacer35Test(int|\PhabelTest\Target\TypeHintReplacer35Test $data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }
private static function testRet175intPhabelTestTargetTypeHintReplacer35Test($data): int|\PhabelTest\Target\TypeHintReplacer35Test { return $data; }

}