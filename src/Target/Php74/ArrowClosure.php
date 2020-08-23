<?php

namespace Phabel\Target\Php74;

use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosure
{
    /**
     * Enter arrow function.
     *
     * @param ArrowFunction $func Arrow function
     *
     * @return Closure
     */
    public static function enterClosure(ArrowFunction $func): Closure
    {
        $nodes = [];
        foreach ($func->getSubNodeNames() as $node) {
            $nodes[$node] = $func->{$node};
        }
        return new Closure($nodes, $func->getAttributes());
    }
}
