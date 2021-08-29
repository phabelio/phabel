<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Tools;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Throw_ as ExprThrow_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Catch_;

/**
 * Polyfill fetching class name from object
 */
class ClassName extends Plugin
{
    public function enter(ClassConstFetch $fetch): ?FuncCall
    {
        if ($fetch->name instanceof Identifier && $fetch->name->name === 'class' && !$fetch->class instanceof Name) {
            return Tools::call('get_class', $fetch->class);
        }
        return null;
    }
}
