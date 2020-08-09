<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class YieldFromReplacer extends NodeVisitorAbstract
{
    /**
     * @var array
     */
    protected $foreachYield;

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Expr\YieldFrom) {
            return;
        }

        $generator = $node->expr;

        return new Node\Expr\Yield_($generator);
    }
}
