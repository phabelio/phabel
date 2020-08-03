<?php

namespace Spatie\Php7to5\NodeVisitors;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use Spatie\Php7to5\Converter;
use Spatie\Php7to5\Exceptions\InvalidPhpCode;

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
            end($this->hasYield)->hasYield = true;
        }
    }
    public function leaveNode(Node $node)
    {
        if ($node instanceof Node\FunctionLike) {
            array_pop($this->hasYield);
        }
    }
}
