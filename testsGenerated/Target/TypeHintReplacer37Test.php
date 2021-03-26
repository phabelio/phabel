<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test181intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function testRet181intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function test182intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function testRet182intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function test183intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function testRet183intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function test184PhabelTestTargetTypeHintReplacer37Test(?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function testRet184PhabelTestTargetTypeHintReplacer37Test($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function test185PhabelTestTargetTypeHintReplacer37Test(?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
function testRet185PhabelTestTargetTypeHintReplacer37Test($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer37Test extends TestCase
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
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[$this, 'testRet181intPhabelTestTargetTypeHintReplacer37Test'], '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet181intPhabelTestTargetTypeHintReplacer37Test'], '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet181intPhabelTestTargetTypeHintReplacer37Test', '123', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[$this, 'testRet182intPhabelTestTargetTypeHintReplacer37Test'], '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet182intPhabelTestTargetTypeHintReplacer37Test'], '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet182intPhabelTestTargetTypeHintReplacer37Test', '123.0', new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[$this, 'testRet183intPhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[[self::class, 'testRet183intPhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
['PhabelTest\Target\testRet183intPhabelTestTargetTypeHintReplacer37Test', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacer37Test => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[[$this, 'testRet184PhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[[self::class, 'testRet184PhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
['PhabelTest\Target\testRet184PhabelTestTargetTypeHintReplacer37Test', $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacer37Test => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[[$this, 'testRet185PhabelTestTargetTypeHintReplacer37Test'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
[[self::class, 'testRet185PhabelTestTargetTypeHintReplacer37Test'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~'],
['PhabelTest\Target\testRet185PhabelTestTargetTypeHintReplacer37Test', null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous returned~']];
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
[fn (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[$this, 'test181intPhabelTestTargetTypeHintReplacer37Test'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test181intPhabelTestTargetTypeHintReplacer37Test'], '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test181intPhabelTestTargetTypeHintReplacer37Test', '123', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[$this, 'test182intPhabelTestTargetTypeHintReplacer37Test'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test182intPhabelTestTargetTypeHintReplacer37Test'], '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test182intPhabelTestTargetTypeHintReplacer37Test', '123.0', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[$this, 'test183intPhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[[self::class, 'test183intPhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
['PhabelTest\Target\test183intPhabelTestTargetTypeHintReplacer37Test', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacer37Test\\|int, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[[$this, 'test184PhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[[self::class, 'test184PhabelTestTargetTypeHintReplacer37Test'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
['PhabelTest\Target\test184PhabelTestTargetTypeHintReplacer37Test', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[[$this, 'test185PhabelTestTargetTypeHintReplacer37Test'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
[[self::class, 'test185PhabelTestTargetTypeHintReplacer37Test'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~'],
['PhabelTest\Target\test185PhabelTestTargetTypeHintReplacer37Test', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacer37Test, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test181intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function testRet181intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function test182intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function testRet182intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function test183intPhabelTestTargetTypeHintReplacer37Test(int|\PhabelTest\Target\TypeHintReplacer37Test $data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function testRet183intPhabelTestTargetTypeHintReplacer37Test($data): int|\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function test184PhabelTestTargetTypeHintReplacer37Test(?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function testRet184PhabelTestTargetTypeHintReplacer37Test($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function test185PhabelTestTargetTypeHintReplacer37Test(?\PhabelTest\Target\TypeHintReplacer37Test $data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }
private static function testRet185PhabelTestTargetTypeHintReplacer37Test($data): ?\PhabelTest\Target\TypeHintReplacer37Test { return $data; }

}