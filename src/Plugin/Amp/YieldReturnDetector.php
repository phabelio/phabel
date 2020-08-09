<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class YieldReturnDetector extends NodeVisitorAbstract
{
    protected $hasYield = [];

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            $this->hasYield []= $node;
        }
        if ($node instanceof Node\Expr\Yield_ ||
            $node instanceof Node\Expr\YieldFrom
        ) {
            \end($this->hasYield)->hasYield = true;
        }
    }
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            \array_pop($this->hasYield);
        }
    }
}
