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
use PhpParser\Node\Scalar\LNumber;

class NestedExpressionFixer extends Plugin
{
    /**
     * Recursively extract bottom ArrayDimFetch.
     *
     * @param Node $var
     * @return Node
     */
    private static function &extractWorkVar(Expr &$var): Expr
    {
        if ($var instanceof ArrayDimFetch && $var->var instanceof ArrayDimFetch) {
            return self::extractWorkVar($var->var);
        }
        return $var;
    }

    public function leave(Expr $expr, Context $context): ?Expr
    {
        /** @var array<string, array<class-string<Expr>, true>> */
        $subNodes = $this->getConfig($class = \get_class($expr), false);
        if (!$subNodes) {
            return null;
        }
        foreach ($subNodes as $key => $types) {
            /** @var Expr $value */
            $value = &$expr->{$key};
            if (!isset($types[\get_class($value)])) {
                $workVar = $this->extractWorkVar($value);
                if (!isset($types[\get_class($workVar)])) {
                    continue;
                }
            }
            switch ($class) {
                case ArrayDimFetch::class:
                case PropertyFetch::class:
                case MethodCall::class:
                case Instanceof_::class:
                    if ($expr instanceof Instanceof_ && $key === 'class') {
                        return self::callPoly('instanceOf', $expr->expr, $expr->class);
                    }
                    $value = self::callPoly('returnMe', $value);
                    break;
                case New_::class:
                case ClassConstFetch::class:
                // For all the following expressions, wrapping in a ternary breaks return-by-ref
                case StaticCall::class:
                case StaticPropertyFetch::class:
                case FuncCall::class:
                    $valueCopy = $value;
                    return new Ternary(
                        new BooleanOr(
                            new Assign($value = $context->getVariable(), $valueCopy),
                            new LNumber(1)
                        ),
                        $expr,
                        new LNumber(0)
                    );
            }
        }
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
     * Check if a is instance of b.
     *
     * @param class-string|object $a
     * @param class-string|object $b
     *
     * @return boolean
     */
    public static function instanceOf($a, $b): bool
    {
        return $a instanceof $b;
    }
}
