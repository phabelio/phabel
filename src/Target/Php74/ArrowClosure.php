<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\VariableFinder;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosure extends Plugin
{
    /**
     * Enter arrow function.
     *
     * @param ArrowFunction $func Arrow function
     *
     * @return Closure
     */
    public function enter(ArrowFunction $func, Context $context): Closure
    {
        $nodes = [];
        foreach ($func->getSubNodeNames() as $node) {
            $nodes[$node] = $func->{$node};
        }
        $nodes['stmts'] = [new Return_($func->expr)];
        $params = [];
        foreach ($nodes['params'] ?? [] as $param) {
            $params[$param->var->name] = true;
        }
        $nodes['uses'] = \array_values(
            \array_intersect_key(
                \array_diff_key(
                    VariableFinder::find($func->expr),
                    $params,
                ),
                $context->variables->top()->getVars()
            )
        );
        return new Closure($nodes, $func->getAttributes());
    }
}
