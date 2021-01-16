<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test81intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet81intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test82intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet82intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test83intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet83intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test84PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet84PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test85PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet85PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }


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
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[$this, 'testRet81intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[self::class, 'testRet81intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet81intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[$this, 'testRet82intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[self::class, 'testRet82intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet82intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[$this, 'testRet83intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[[self::class, 'testRet83intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet83intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet84PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet84PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet84PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet85PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet85PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet85PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~']];
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
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[$this, 'test81intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[self::class, 'test81intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test81intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[$this, 'test82intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[self::class, 'test82intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test82intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[$this, 'test83intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[[self::class, 'test83intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test83intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest\\|int, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test84PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test84PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test84PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test85PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test85PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test85PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test81intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet81intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test82intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet82intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test83intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet83intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test84PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet84PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test85PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet85PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }

}