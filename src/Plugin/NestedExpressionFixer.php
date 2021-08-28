<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;

/**
 * Fix nested expressions.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NestedExpressionFixer extends Plugin
{
    /**
     * Recursively extract bottom ArrayDimFetch.
     *
     * @param Node $var
     * @return Node
     */
    private static function &extractWorkVar(Expr &$var)
    {
        if ($var instanceof ArrayDimFetch && $var->var instanceof ArrayDimFetch) {
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
    public function leave(Expr $expr, Context $context)
    {
        /** @var array<string, array<class-string<Expr>, true>> */
        $subNodes = $this->getConfig($class = \get_class($expr), false);
        if (!$subNodes) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof Expr || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        foreach ($subNodes as $key => $types) {
            /** @var Expr $value */
            $value =& $expr->{$key};
            if (!isset($types[IssetExpressionFixer::getClass(isset($value) ? $value : '')])) {
                if (!$value instanceof Expr) {
                    continue;
                }
                $workVar =& $this->extractWorkVar($value);
                if (!isset($types[\get_class($workVar)])) {
                    continue;
                }
            } else {
                $workVar =& $value;
            }
            switch (\get_class($workVar)) {
                case Throw_::class:
                    $workVar = self::callPoly('throwMe', $workVar->expr);
                    continue 2;
            }
            switch ($class) {
                case ArrayDimFetch::class:
                case PropertyFetch::class:
                case MethodCall::class:
                case Instanceof_::class:
                    if ($expr instanceof Instanceof_ && $key === 'class') {
                        $phabelReturn = self::callPoly('instanceOf', $expr->expr, $expr->class);
                        if (!($phabelReturn instanceof Expr || \is_null($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        return $phabelReturn;
                    }
                    $value = self::callPoly('returnMe', $value);
                    break;
                case New_::class:
                case ClassConstFetch::class:
                    $valueCopy = $value;
                    $phabelReturn = new Ternary(new BooleanOr(new Assign($value = $context->getVariable(), $valueCopy), self::fromLiteral(true)), $expr, self::fromLiteral(false));
                    if (!($phabelReturn instanceof Expr || \is_null($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    return $phabelReturn;
                case StaticCall::class:
                case StaticPropertyFetch::class:
                case FuncCall::class:
                    $valueCopy = $value;
                    $context->insertBefore($expr, new Assign($value = $context->getVariable(), $valueCopy));
                    break;
                default:
                    throw new \RuntimeException("Trying to fix unknown nested expression {$class}");
            }
        }
        $phabelReturn = null;
        if (!($phabelReturn instanceof Expr || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Expr, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns the data provided.
     *
     * @param mixed $data Data
     *
     * @return mixed
     *
     * @template T
     *
     * @psalm-param T $data data
     *
     * @psalm-return T
     */
    public static function returnMe($data)
    {
        return $data;
    }
    /**
     * Throws the exception provided.
     *
     * @param \Throwable $throwable
     *
     * @return void
     *
     * @template T
     *
     * @psalm-param T $data data
     */
    public static function throwMe(\Throwable $throwable)
    {
        throw $throwable;
    }
    /**
     * Check if a is instance of b.
     *
     * @param class-string|object $a
     * @param class-string|object $b
     *
     * @return boolean
     */
    public static function instanceOf($a, $b)
    {
        $phabelReturn = \Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($a, $b);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function next(array $config)
    {
        $phabelReturn = [NewFixer::class];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
