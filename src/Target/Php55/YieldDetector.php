<?php

namespace Phabel\Target\Php55;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ReGenerator;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;

/**
 * Detect usages of yield.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
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
    public static function runBefore(): array
    {
        return [ReGenerator::class];
    }
    public static function runAfter(): array
    {
        return [ArrowClosure::class];
    }
}
