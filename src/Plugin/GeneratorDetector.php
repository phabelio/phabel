<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\PhpParser\Node\Expr\Yield_;
use Phabel\PhpParser\Node\Expr\YieldFrom;
use Phabel\PhpParser\Node\FunctionLike;
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
    /**
     * Return whether this function is a generator.
     *
     * @param FunctionLike $node
     * @return boolean
     */
    public static function isGenerator(FunctionLike $node)
    {
        $phabelReturn = $node->getAttribute(self::IS_GENERATOR, \false);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    public function enterYield(Yield_ $node, Context $ctx)
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, \true);
                return;
            }
        }
    }
    public function enterYieldFrom(YieldFrom $node, Context $ctx)
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(self::IS_GENERATOR, \true);
                return;
            }
        }
    }
}
