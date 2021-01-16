<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test0callable(callable $data): callable { return $data; }
function testRet0callable($data): callable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacer0Test extends TestCase
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
[fn ($data): callable => $data, "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet0callable'], "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet0callable'], "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet0callable', "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~']];
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
[fn (callable $data): callable => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test0callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test0callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test0callable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~']];
;
    }
    
    public static function noop() {}
    
    private static function test0callable(callable $data): callable { return $data; }
private static function testRet0callable($data): callable { return $data; }

}