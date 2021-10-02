<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php70\NullCoalesce\DisallowedExpressions;
use Phabel\Target\Php74\NullCoalesceAssignment;
use Phabel\Target\Php80\NullSafeTransformer;
use Phabel\Tools;
use PhabelVendor\PhpParser\Node\Expr;
use PhabelVendor\PhpParser\Node\Expr\ArrayDimFetch;
use PhabelVendor\PhpParser\Node\Expr\Assign;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhabelVendor\PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhabelVendor\PhpParser\Node\Expr\Isset_;
use PhabelVendor\PhpParser\Node\Expr\PropertyFetch;
use PhabelVendor\PhpParser\Node\Expr\Ternary;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceReplacer extends Plugin
{
    /**
     * Recursively extract bottom ArrayDimFetch.
     *
     * @param Expr $var
     * @return Expr
     */
    private static function &extractWorkVar(Expr &$var) : Expr
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
    public function enter(Coalesce $node, Context $ctx) : Ternary
    {
        if (!Tools::hasSideEffects($workVar =& self::extractWorkVar($node->left)) && !isset(DisallowedExpressions::EXPRESSIONS[\get_class($node->left)])) {
            return new Ternary(new Isset_([$node->left]), $node->left, $node->right);
        }
        $valueCopy = $workVar;
        $check = new NotIdentical(Tools::fromLiteral(null), new Assign($workVar = $ctx->getVariable(), $valueCopy));
        return new Ternary($node->left === $workVar ? $check : new BooleanAnd($check, new Isset_([$node->left])), $node->left, $node->right);
    }
    public static function withPrevious(array $config) : array
    {
        return [NullCoalesceAssignment::class, NullSafeTransformer::class];
    }
}
