<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;

/**
 * Fix certain compound access statements.
 */
class CompoundAccess extends Plugin
{
    /**
     * Replace non-simple isset calls.
     *
     * @param Isset_ $node Isset node
     *
     * @return void
     */
    public function enterIsset(Isset_ $node): void
    {
        foreach ($node->vars as &$var) {
            if ($var instanceof ArrayDimFetch 
                && $var->var instanceof Expr
                && !($var->var instanceof Variable || $var->var instanceof FuncCall) {
                $var->var = self::callPoly('returnMe', $var->var);
            }
        }
    }
    /**
     * Fix yield array access.
     *
     * @param ArrayDimFetch $node Node
     *
     * @return void
     */
    public function enterArrayYield(ArrayDimFetch $node): void
    {
        if (!$node->var instanceof Node\Expr\Yield_) {
            return;
        }
        $node->var = self::callPoly('returnMe', $node->var);
    }
    /**
     * Fix yield array access.
     *
     * @param Yield_ $node Yield
     *
     * @return void
     */
    public function enterYield(Yield_ $node): void
    {
        $value = &$node->value;
        if ($value instanceof Node\Expr\Variable && $value->name !== "this") {
            return;
        }
        if ($value instanceof Node\Expr\FuncCall ||
            $value instanceof Node\Expr\MethodCall ||
            $value instanceof Node\Expr\StaticCall ||
            $value instanceof Node\Scalar
        ) {
            return;
        }
        $value = self::callPoly('returnMe', $value);
    }
    /**
     * Replace method call on yielded|cloned|closure object.
     *
     * @param MethodCall $node Method call
     *
     * @return void
     */
    public function enterMethodCall(MethodCall $node): void
    {
        $value = &$node->var;
        if (!$value instanceof Node\Expr\Clone_ &&
            !$value instanceof Node\Expr\Yield_ &&
            !$value instanceof Node\Expr\Closure
        ) {
            return;
        }
        $value = self::callPoly('returnMe', $value);
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
}
