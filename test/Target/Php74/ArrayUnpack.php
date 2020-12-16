<?php

namespace PhabelTest\Target\Php74;

use Phabel\Context;
use PHPUnit\Framework\TestCase;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ArrayUnpack extends TestCase
{
    public function test()
    {
        $b = ['b' => 'bb'];
        $this->assertEquals(['a' => 'aa', 'b' => 'bb', 'c' => 'cc'], ['a' => 'aa', ...$b, 'c' => 'cc']);
        $this->assertEquals(['a' => 'aa', 'b' => 'bb', 'c' => 'cc'], ['a' => 'aa', ...(fn () => $b)(), 'c' => 'cc']);
    }
}
