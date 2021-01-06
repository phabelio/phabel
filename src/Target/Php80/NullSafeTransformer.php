<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ArrowClosureVariableFinder;
use Phabel\Plugin\NestedExpressionFixer;
use Phabel\Target\Php70\NullCoalesceReplacer;
use Phabel\Traverser;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Throw_;

/**
 * Polyfill nullsafe expressions
 */
class NullSafeTransformer extends Plugin
{
    /**
     * Property fetch
     *
     * @param NullsafePropertyFetch $fetch
     * @return PropertyFetch
     */
    public function enterPropertyFetch(NullsafePropertyFetch $fetch): PropertyFetch
    {
        return new PropertyFetch(new Coalesce($fetch->var, self::callPoly('nullClass')), $fetch->name);
    }

    /**
     * Method call
     *
     * @param NullsafeMethodCall $fetch
     * @return MethodCall
     */
    public function enterMethodCall(NullsafeMethodCall $fetch): MethodCall
    {
        return new MethodCall(new Coalesce($fetch->var, self::callPoly('nullClass')), $fetch->name, $fetch->args);
    }

    /**
     * Return and memoize nullsafe class
     *
     * @return NullSafe
     */
    public static function nullClass(): NullSafe
    {
        static $safe;
        $safe ??= new NullSafe;
        return $safe;
    }
}
