<?php

namespace PhabelTest\Target\Php70;

use Phabel\Context;
use PHPUnit\Framework\TestCase;
use Phabel\Target\Php74\NullCoalesceAssignment;
use Phabel\Tools;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Ternary;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceReplacer extends TestCase
{
    public function test()
    {
        $this->assertTrue(null ?? true);
        $this->assertFalse(false ?? true);
        $this->assertFalse(false ?? null);

        $this->assertTrue((fn () => null)() ?? true);
        $this->assertFalse((fn () => false)() ?? true);
        $this->assertFalse((fn () => false)() ?? null);

        $arr = ['a' => ['b' => new class {
            public function __construct()
            {
                $this->c = new class extends \ArrayAccess {
                    public function __construct()
                    {
                        $this->d = ['e' => true, 'e2' => false];
                    }
                    public function offsetExists($offset): bool {
                        return false;
                    }
                    public function offsetGet($offset) {
                        return 'uwu';
                    }
                    public function offsetSet($offset, $val) {

                    }
                    public function offsetUnset($offset) {}
                };
            }
        }]];

        $this->assertTrue($arr['a']['b']->c->d['e'] ?? false);
        $this->assertFalse($arr['a']['b']->c->d['e2'] ?? null);
        $this->assertFalse($arr['a']['b']->c->d['e']['f'] ?? false);

        $this->assertEquals('test', $arr['a']['b']->c->pony ?? 'test');

        $this->assertTrue((fn () => $arr)()['a']['b']->c->d['e'] ?? false);
        $this->assertFalse((fn () => $arr)()['a']['b']->c->d['e2'] ?? null);
        $this->assertFalse((fn () => $arr)()['a']['b']->c->d['e']['f'] ?? false);

        $this->assertEquals('test', (fn () => $arr)()['a']['b']->c->pony ?? 'test');
    }
}
