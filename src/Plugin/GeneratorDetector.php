<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhabelVendor\PhpParser\Node\Expr\Yield_;
use PhabelVendor\PhpParser\Node\Expr\YieldFrom;
use PhabelVendor\PhpParser\Node\FunctionLike;
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
    private const IS_GENERATOR = 'isGenerator';
    /**
     * Return whether this function is a generator.
     *
     * @param FunctionLike $node
     * @return boolean
     */
    public static function isGenerator(FunctionLike $node) : bool
    {
        return $node->getAttribute(self::IS_GENERATOR, \false);
    }
    /**
     *
     */
    public function enterYield(Yield_ $node, Context $ctx) : void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, \true);
                return;
            }
        }
    }
    /**
     *
     */
    public function enterYieldFrom(YieldFrom $node, Context $ctx) : void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, \true);
                return;
            }
        }
    }
}
