<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;

/**
 * Detect usages of yield and yield from.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class GeneratorDetector extends Plugin
{
    /**
     * Whether this function is a generator.
     */
    const IS_GENERATOR = 'isGenerator';
    public function enterYield(Yield_ $node, Context $ctx): void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, true);
                return;
            }
        }
    }
    public function enterYieldFrom(YieldFrom $node, Context $ctx): void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, true);
                return;
            }
        }
    }
}
