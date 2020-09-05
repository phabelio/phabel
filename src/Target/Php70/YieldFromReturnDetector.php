<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ReGenerator;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Return_;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class YieldFromReturnDetector extends Plugin
{
    public function enterYieldFrom(YieldFrom $node, Context $ctx): void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute(ReGenerator::SHOULD_ATTRIBUTE, true);
                return;
            }
        }
    }
    public function enterYield(Yield_ $node, Context $ctx): void
    {
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute('hasYield', true);
                if ($parent->getAttribute('hasReturn')) {
                    $parent->setAttribute(ReGenerator::SHOULD_ATTRIBUTE, true);
                }
                return;
            }
        }
    }
    public function enterReturn(Return_ $node, Context $ctx): void
    {
        if (!$node->expr) {
            return;
        }
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof FunctionLike) {
                $parent->setAttribute('hasReturn', true);
                if ($parent->getAttribute('hasYield')) {
                    $parent->setAttribute(ReGenerator::SHOULD_ATTRIBUTE, true);
                }
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
