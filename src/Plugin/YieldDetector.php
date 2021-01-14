<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
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
}
