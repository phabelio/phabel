<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php74\NullCoalesceAssignment;
use Phabel\Target\Php80\NullSafeTransformer;
use Phabel\Tools;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Ternary;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceReplacer extends Plugin
{
    /**
     * Recursively extract bottom ArrayDimFetch.
     *
     * @param Node $var
     * @return Node
     */
    private static function &extractWorkVar(Expr &$var): Expr
    {
        if ($var instanceof ArrayDimFetch) {
            return self::extractWorkVar($var->var);
        }
        if ($var instanceof PropertyFetch) {
            return self::extractWorkVar($var->var);
        }
        return $var;
    }
    /**
     * Replace null coalesce.
     *
     * @param Coalesce $node Coalesce
     *
     * @return Ternary
     */
    public function enter(Coalesce $node, Context $ctx): Ternary
    {
        if (!Tools::hasSideEffects($workVar = &self::extractWorkVar($node->left))) {
            return new Ternary(new Isset_([$node->left]), $node->left, $node->right);
        }
        $valueCopy = $workVar;
        $check = new NotIdentical(
            Tools::toLiteral(null),
            new Assign($workVar = $ctx->getVariable(), $valueCopy)
        );
        return new Ternary(
            $node->left === $workVar ? $check : new BooleanAnd(
                $check,
                new Isset_([$node->left])
            ),
            $node->left,
            $node->right
        );
    }
    public static function runWithAfter(array $config): array
    {
        return [NullCoalesceAssignment::class, NullSafeTransformer::class];
    }
}
