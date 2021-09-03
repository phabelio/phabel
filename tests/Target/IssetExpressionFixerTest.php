<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class IssetExpressionFixerTest extends TestCase
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
        isset($a[throw new \Exception('lmao')]);
    }
    public function testExprThrowSmore()
    {
        $this->expectExceptionMessage('lmao');
        isset(${throw new \Exception('lmao')});
    }
    public function testArrayDimFetch()
    {
        $this->assertTrue(isset(['a' => true]['a']));
        $this->assertTrue(isset(($a = ['a' => true])['a']));
        $this->assertFalse(isset((new class extends \ArrayObject{})[(fn ($a) => $a)('a')]));
    }
    public function testStaticPropertyFetch()
    {
        $this->assertTrue(isset((clone $this)::${(string)clone new class {
            public function __toString()
            {
                return 'a';
            }
        }}));
    }
    public function testPropertyFetch()
    {
        $this->assertTrue(isset((clone new class {
            public $a = true;
        })->a));
    }
}
