<?php

namespace PhabelTest\Target\Php74;

use PHPUnit\Framework\TestCase;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce as BinaryOpCoalesce;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceAssignment extends TestCase
{
    public function test()
    {
        $b = null;
        $c = ['a' => []];

        $a ??= 'lmao';
        $b ??= 'lmao';
        $c['a']['b'] ??= 'lmao';
        $this->assertEquals('lmao', $a);
        $this->assertEquals('lmao', $b);
        $this->assertEquals('lmao', $c['a']['b']);
    }
}
