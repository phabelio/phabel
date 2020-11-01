<?php

namespace Phabel\Target\Php71;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php73\ListReference;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;

/**
 * Polyfills list expression return value.
 */
class ListExpression extends Plugin
{
    /**
     * Parse list assignment.
     *
     * @param Assign $node List assignment
     *
     * @return void
     */
    public function enterAssign(Assign $node, Context $ctx): void
    {
        $isStmt = $ctx->parents[0]->getAttribute('currentNode') === 'stmts';
        if (!($node->var instanceof List_ || $node->var instanceof Array_) || $isStmt) {
            return;
        }
        $list = $node->var;
        $var = $ctx->getVariable();
        $assignments = ListReference::splitList($list, $var);

        // On newer versions of php, the list assignment expression returns the original array
        $ctx->insertBefore($node, new Assign($var, $node->expr), ...$assignments);
        return $var;
    }
}
