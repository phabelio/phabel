<?php

namespace Phabel\Target\Php55;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ReGenerator;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;

class YieldDetector extends Plugin
{
    public function enterYield(Yield_ $node, Context $ctx): void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(ReGenerator::SHOULD_ATTRIBUTE, true);
                return;
            }
        }
    }
    public function runBefore(): array
    {
        return [ReGenerator::class];
    }
    public function runAfter(): array
    {
        return [ArrowClosure::class];
    }
}
