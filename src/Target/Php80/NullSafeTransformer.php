<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Param;

/**
 * Polyfill nullsafe expressions.
 */
class NullSafeTransformer extends Plugin
{
    /**
     * Property fetch.
     *
     * @param NullsafePropertyFetch $fetch
     * @return PropertyFetch
     */
    public function enterPropertyFetch(NullsafePropertyFetch $fetch): PropertyFetch
    {
        return new PropertyFetch(new Coalesce($fetch->var, self::callPoly('nullClass')), $fetch->name);
    }

    /**
     * Method call.
     *
     * @param NullsafeMethodCall $fetch
     * @return MethodCall
     */
    public function enterMethodCall(NullsafeMethodCall $fetch): MethodCall
    {
        return new MethodCall(new Coalesce($fetch->var, self::callPoly('nullClass')), $fetch->name, $fetch->args);
    }

    /**
     * Return and memoize nullsafe class.
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
