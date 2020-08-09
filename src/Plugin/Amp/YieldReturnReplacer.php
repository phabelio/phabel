<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class YieldReturnReplacer extends NodeVisitorAbstract
{
    protected $functions = [];
    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            $this->functions[] = $node;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            \array_pop($this->functions);
            return;
        }
        if (!$node instanceof Node\Stmt\Return_) {
            return;
        }
        if ($node->expr === null) {
            return new Node\Stmt\Return_();
        }

        if (!(\end($this->functions)->hasYield ?? false)) {
            return;
        }

        $value = $node->expr;

        $newReturn = new Node\Expr\Yield_(
            new Node\Expr\New_(
                new Node\Expr\ConstFetch(
                    new Node\Name('\YieldReturnValue')
                ),
                [$value]
            )
        );

        $stmts = [$newReturn, new Node\Stmt\Return_()];
        return $stmts;
    }
}
