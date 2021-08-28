<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\Stmt\Expression;
/**
 * Wraps standalone expressions in statements into Stmt\Expressions.
 */
class StmtExprWrapper extends Plugin
{
    public function enter(Expr $expr, Context $ctx)
    {
        if ($ctx->parents[0]->getAttribute('currentNode') === 'stmts') {
            $phabelReturn = new Expression($expr);
            if (!($phabelReturn instanceof Expression || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expression, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof Expression || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expression, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
