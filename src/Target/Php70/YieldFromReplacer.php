<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\While_;

class YieldFromReplacer extends Plugin
{
    private string $phabelVar = '';
    private int $phabelCount = 0;
    public function shouldRunFile(string $file): bool
    {
        $this->phabelVar = 'phabelGeneratorYieldFrom'.\hash('sha256', $file);
        return parent::shouldRunFile($file);
    }
    public function enterNode(YieldFrom $node, Context $ctx)
    {
        $var = new Variable($this->phabelVar.($this->phabelCount++));
        $assign = new Assign($var, $node->expr);
        $ifInstanceof = new If_(new Instanceof_(Plugin::callMethod($var, 'valid'), new FullyQualified(\YieldReturnValue::class)), ['stmts' => [new Assign()]]);
        $while = new While_(new MethodCall($var, new Identifier('valid')));
        foreach ($ctx->parents as $node) {
            if ($node->hasAttribute('currentNodeIndex')) {
                $ctx->insertBefore($node, $node->expr);
            }
        }
        $generator = $node->expr;

        return new Node\Expr\Yield_($generator);
    }
}
