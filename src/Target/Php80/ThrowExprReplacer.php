<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\PhpParser\Node\Expr\StaticCall;
use Phabel\PhpParser\Node\Expr\Throw_ as ExprThrow_;
/**
 * Polyfill throw expression.
 */
class ThrowExprReplacer extends Plugin
{
    public function enter(ExprThrow_ $throw)
    {
        $phabelReturn = self::callPoly('throwMe', $throw->expr);
        if (!$phabelReturn instanceof StaticCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function throwMe(\Throwable $expr)
    {
        throw $expr;
    }
}
