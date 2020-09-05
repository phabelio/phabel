<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ArrowClosureVariableFinder;
use Phabel\Traverser;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosure
{
    /**
     * Traverser.
     */
    private Traverser $traverser;
    /**
     * Finder plugin.
     */
    private ArrowClosureVariableFinder $finderPlugin;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->finderPlugin = new ArrowClosureVariableFinder;
        $this->traverser = Traverser::fromPlugin($this->finderPlugin);
    }
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
        $params = [];
        foreach ($nodes['params'] ?? [] as $param) {
            $params[$param->var->name] = true;
        }
        $this->traverser->traverseAst($func->expr);
        $nodes['uses'] = \array_keys(
            \array_intersect_key(
                \array_diff_key(
                    $this->finderPlugin->getFound(),
                    $params,
                ),
                $context->variables->top()->getVariables()
            )
        );
        return new Closure($nodes, $func->getAttributes());
    }
}