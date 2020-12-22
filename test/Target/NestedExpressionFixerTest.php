<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

class a
{
}
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NestedExpressionFixerTest extends TestCase
{
    const A = true;
    public static $a = true;
    public static function a()
    {
        return true;
    }
    public function testExprThrow()
    {
        $this->expectExceptionMessage('lmao');
        $a ?? throw new \Exception('lmao');
    }
    public function testExprThrowSmore()
    {
        $this->expectDeprecationMessage('lmao');
        1 + throw new \Exception('lmao');
    }
    public function testClassInstanceof()
    {
        $this->assertTrue(new a instanceof (new a));
        $this->assertTrue($this instanceof (eval("return \$this;")));
        $this->assertTrue($this instanceof ($a = $this));
        $this->assertTrue(new a instanceof ("\\PhabelTest\\Target\\a"));
    }
    public function testNew()
    {
        $this->assertTrue((new ($a = a::class)) instanceof (new a));
    }
    public function testArrayDimFetch()
    {
        $this->assertTrue(['a' => true]['a']);
        $this->assertTrue(($a = ['a' => true])['a']);
        $this->assertTrue(($a = ['a' => true])[(fn ($a) => $a)('a')]);
    }
    public function testMethodCall()
    {
        $this->assertTrue((clone new class {
            public function a()
            {
                return true;
            }
        })->{(clone new class {
            public function a()
            {
                return 'a';
            }
        })->a()}());
    }
    public function testStaticCall()
    {
        $this->assertTrue((clone $this)::{(string)clone new class {
            public function __toString()
            {
                return 'a';
            }
        }}());
    }
    public function testStaticPropertyFetch()
    {
        $this->assertTrue((clone $this)::${(string)clone new class {
            public function __toString()
            {
                return 'a';
            }
        }});
    }
    public function testClassConstFetch()
    {
        $this->assertTrue((clone $this)::A);
    }
    public function testPropertyFetch()
    {
        $this->assertTrue((clone new class {
            public $a = true;
        })->a);
    }
    public function testFuncCall()
    {
        $this->assertTrue((fn () => true)());
    }
}
