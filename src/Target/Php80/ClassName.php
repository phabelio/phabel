<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Tools;
use PhabelVendor\PhpParser\Node\Expr\ClassConstFetch;
use PhabelVendor\PhpParser\Node\Expr\FuncCall;
use PhabelVendor\PhpParser\Node\Identifier;
use PhabelVendor\PhpParser\Node\Name;
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
