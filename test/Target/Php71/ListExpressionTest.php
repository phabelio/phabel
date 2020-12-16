<?php

namespace PhabelTest\Target\Php71;

use Phabel\Context;
use Phabel\Target\Php73\ListReference;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PHPUnit\Framework\TestCase;

/**
 * Polyfills list expression return value.
 */
class ListExpressionTest extends TestCase
{
    public function test()
    {
        $arr = ['a', ['b' => 'bb', 'c' => ['d', 'e']]];
        $this->assertEquals($arr, [$a, ['b' => $b, 'c' => [$d, $e]]] = $arr);
        $this->assertEquals('a', $a);
        $this->assertEquals('bb', $b);
        $this->assertEquals('d', $d);
        $this->assertEquals('e', $e);
    }
    /**
     * Parse list assignment.
     *
     * @param Assign $node List assignment
     *
     * @return ?Variable
     */
    public function enterAssign(Assign $node, Context $ctx): ?Variable
    {
        $isStmt = $ctx->parents[0]->getAttribute('currentNode') === 'stmts';
        if (!($node->var instanceof List_ || $node->var instanceof Array_) || $isStmt) {
            return null;
        }
        $list = $node->var;
        $var = $ctx->getVariable();
        $assignments = ListReference::splitList($list, $var);

        // On newer versions of php, the list assignment expression returns the original array
        $ctx->insertBefore($node, new Assign($var, $node->expr), ...$assignments);
        return $var;
    }
}
