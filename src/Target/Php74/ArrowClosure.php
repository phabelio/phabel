<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\VariableFinder;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
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
        /** @var array<string, mixed> */
        $nodes = [];
        /** @var string */
        foreach ($func->getSubNodeNames() as $node) {
            /** @var mixed */
            $nodes[$node] = $func->{$node};
        }
        $nodes['stmts'] = [new Return_($func->expr)];
        /** @var array<string, true> */
        $params = [];
        /** @var Param */
        foreach ($nodes['params'] ?? [] as $param) {
            if ($param->var instanceof Variable) {
                /** @var string $param->var->name */
                $params[$param->var->name] = true;
            }
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
