<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use PhabelVendor\PhpParser\Node\Expr\StaticCall;
use PhabelVendor\PhpParser\Node\Expr\Throw_ as ExprThrow_;
/**
 * Polyfill throw expression.
 */
class ThrowExprReplacer extends Plugin
{
    /**
     *
     */
    public function enter(ExprThrow_ $throw) : StaticCall
    {
        return self::callPoly('throwMe', $throw->expr);
    }
    /**
     *
     */
    public static function throwMe(\Throwable $expr) : void
    {
        throw $expr;
    }
}
