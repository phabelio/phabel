<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test81intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function testRet81intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function test82intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function testRet82intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function test83intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function testRet83intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function test84PhabelTestTargetTypeHintReplacer17Test(?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function testRet84PhabelTestTargetTypeHintReplacer17Test($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function test85PhabelTestTargetTypeHintReplacer17Test(?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
function testRet85PhabelTestTargetTypeHintReplacer17Test($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer17Test extends TestCase
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
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[$this, 'testRet81intPhabelTestTargetTypeHintReplacer17Test'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet81intPhabelTestTargetTypeHintReplacer17Test'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet81intPhabelTestTargetTypeHintReplacer17Test', 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[$this, 'testRet82intPhabelTestTargetTypeHintReplacer17Test'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet82intPhabelTestTargetTypeHintReplacer17Test'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet82intPhabelTestTargetTypeHintReplacer17Test', -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[$this, 'testRet83intPhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet83intPhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet83intPhabelTestTargetTypeHintReplacer17Test', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacer17Test => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[[$this, 'testRet84PhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[[self::class, 'testRet84PhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
['PhabelTest\Target\testRet84PhabelTestTargetTypeHintReplacer17Test', $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacer17Test => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[[$this, 'testRet85PhabelTestTargetTypeHintReplacer17Test'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
[[self::class, 'testRet85PhabelTestTargetTypeHintReplacer17Test'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~'],
['PhabelTest\Target\testRet85PhabelTestTargetTypeHintReplacer17Test', null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous returned~']];
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
[fn (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[$this, 'test81intPhabelTestTargetTypeHintReplacer17Test'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test81intPhabelTestTargetTypeHintReplacer17Test'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test81intPhabelTestTargetTypeHintReplacer17Test', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[$this, 'test82intPhabelTestTargetTypeHintReplacer17Test'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test82intPhabelTestTargetTypeHintReplacer17Test'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test82intPhabelTestTargetTypeHintReplacer17Test', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[$this, 'test83intPhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test83intPhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test83intPhabelTestTargetTypeHintReplacer17Test', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer17Test\\|int, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[[$this, 'test84PhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[[self::class, 'test84PhabelTestTargetTypeHintReplacer17Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
['PhabelTest\Target\test84PhabelTestTargetTypeHintReplacer17Test', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[[$this, 'test85PhabelTestTargetTypeHintReplacer17Test'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
[[self::class, 'test85PhabelTestTargetTypeHintReplacer17Test'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~'],
['PhabelTest\Target\test85PhabelTestTargetTypeHintReplacer17Test', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer17Test, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test81intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function testRet81intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function test82intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function testRet82intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function test83intPhabelTestTargetTypeHintReplacer17Test(int|\PhabelTest\Target\TypeHintReplacer17Test $data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function testRet83intPhabelTestTargetTypeHintReplacer17Test($data): int|\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function test84PhabelTestTargetTypeHintReplacer17Test(?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function testRet84PhabelTestTargetTypeHintReplacer17Test($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function test85PhabelTestTargetTypeHintReplacer17Test(?\PhabelTest\Target\TypeHintReplacer17Test $data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }
private static function testRet85PhabelTestTargetTypeHintReplacer17Test($data): ?\PhabelTest\Target\TypeHintReplacer17Test { return $data; }

}