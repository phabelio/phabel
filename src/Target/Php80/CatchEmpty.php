<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Throw_ as ExprThrow_;
use PhpParser\Node\Stmt\Catch_;

/**
 * Polyfill empty catch expression.
 */
class CatchEmpty extends Plugin
{
    public function enter(Catch_ $catch_, Context $ctx): void
    {
        if ($catch_->var === null) {
            $catch_->var = $ctx->getVariable();
        }
    }
}
