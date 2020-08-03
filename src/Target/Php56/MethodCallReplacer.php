<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class MethodCallReplacer extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Expr\MethodCall) {
            return;
        }
        $value = &$node->var;
        if (!$value instanceof Node\Expr\Clone_ &&
            !$value instanceof Node\Expr\Yield_ &&
            !$value instanceof Node\Expr\Closure
        ) {
            return;
        }
        $value = new Node\Expr\FuncCall(new Node\Name('\\returnMe'), [$value]);
        return $node;
    }
}
