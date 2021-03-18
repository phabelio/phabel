<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test176intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function testRet176intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function test177intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function testRet177intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function test178intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function testRet178intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function test179intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function testRet179intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function test180intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
function testRet180intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer36Test extends TestCase
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
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[$this, 'testRet176intPhabelTestTargetTypeHintReplacer36Test'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet176intPhabelTestTargetTypeHintReplacer36Test'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet176intPhabelTestTargetTypeHintReplacer36Test', -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[$this, 'testRet177intPhabelTestTargetTypeHintReplacer36Test'], 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet177intPhabelTestTargetTypeHintReplacer36Test'], 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet177intPhabelTestTargetTypeHintReplacer36Test', 123.123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[$this, 'testRet178intPhabelTestTargetTypeHintReplacer36Test'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet178intPhabelTestTargetTypeHintReplacer36Test'], 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet178intPhabelTestTargetTypeHintReplacer36Test', 1e3, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[$this, 'testRet179intPhabelTestTargetTypeHintReplacer36Test'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet179intPhabelTestTargetTypeHintReplacer36Test'], true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet179intPhabelTestTargetTypeHintReplacer36Test', true, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[$this, 'testRet180intPhabelTestTargetTypeHintReplacer36Test'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet180intPhabelTestTargetTypeHintReplacer36Test'], false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet180intPhabelTestTargetTypeHintReplacer36Test', false, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous returned~']];
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
[fn (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[$this, 'test176intPhabelTestTargetTypeHintReplacer36Test'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test176intPhabelTestTargetTypeHintReplacer36Test'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test176intPhabelTestTargetTypeHintReplacer36Test', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[$this, 'test177intPhabelTestTargetTypeHintReplacer36Test'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test177intPhabelTestTargetTypeHintReplacer36Test'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test177intPhabelTestTargetTypeHintReplacer36Test', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[$this, 'test178intPhabelTestTargetTypeHintReplacer36Test'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test178intPhabelTestTargetTypeHintReplacer36Test'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test178intPhabelTestTargetTypeHintReplacer36Test', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[$this, 'test179intPhabelTestTargetTypeHintReplacer36Test'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test179intPhabelTestTargetTypeHintReplacer36Test'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test179intPhabelTestTargetTypeHintReplacer36Test', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[$this, 'test180intPhabelTestTargetTypeHintReplacer36Test'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test180intPhabelTestTargetTypeHintReplacer36Test'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test180intPhabelTestTargetTypeHintReplacer36Test', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer36Test\\|int, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test176intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function testRet176intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function test177intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function testRet177intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function test178intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function testRet178intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function test179intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function testRet179intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function test180intPhabelTestTargetTypeHintReplacer36Test(int|\PhabelTest\Target\TypeHintReplacer36Test $data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }
private static function testRet180intPhabelTestTargetTypeHintReplacer36Test($data): int|\PhabelTest\Target\TypeHintReplacer36Test { return $data; }

}