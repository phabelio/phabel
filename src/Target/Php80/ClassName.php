<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Tools;
use Phabel\PhpParser\Node\Expr\ClassConstFetch;
use Phabel\PhpParser\Node\Expr\FuncCall;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
/**
 * Polyfill fetching class name from object.
 */
class ClassName extends Plugin
{
    public function enter(ClassConstFetch $fetch) : ?FuncCall
    {
        if ($fetch->name instanceof Identifier && $fetch->name->name === 'class' && !$fetch->class instanceof Name) {
            return Tools::call('get_class', $fetch->class);
        }
        return null;
    }
}
