<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php70\NullCoalesce\DisallowedExpressions;
use Phabel\Target\Php74\NullCoalesceAssignment;
use Phabel\Target\Php80\NullSafeTransformer;
use Phabel\Tools;
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
     * @param Expr $var
     * @return Expr
     */
    private static function &extractWorkVar(Expr &$var)
    {
        if ($var instanceof ArrayDimFetch) {
            $phabelReturn =& self::extractWorkVar($var->var);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($var instanceof PropertyFetch) {
            $phabelReturn =& self::extractWorkVar($var->var);
            if (!$phabelReturn instanceof Expr) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn =& $var;
        if (!$phabelReturn instanceof Expr) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Replace null coalesce.
     *
     * @param Coalesce $node Coalesce
     *
     * @return Ternary
     */
    public function enter(Coalesce $node, Context $ctx)
    {
        if (!Tools::hasSideEffects($workVar =& self::extractWorkVar($node->left)) && !isset(DisallowedExpressions::EXPRESSIONS[\get_class($node->left)])) {
            $phabelReturn = new Ternary(new Isset_([$node->left]), $node->left, $node->right);
            if (!$phabelReturn instanceof Ternary) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Ternary, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $valueCopy = $workVar;
        $check = new NotIdentical(Tools::fromLiteral(null), new Assign($workVar = $ctx->getVariable(), $valueCopy));
        $phabelReturn = new Ternary($node->left === $workVar ? $check : new BooleanAnd($check, new Isset_([$node->left])), $node->left, $node->right);
        if (!$phabelReturn instanceof Ternary) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Ternary, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function withPrevious(array $config)
    {
        $phabelReturn = [NullCoalesceAssignment::class, NullSafeTransformer::class];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
