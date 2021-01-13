<?php

namespace Phabel\Target\Php74;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ArrayUnpack extends Plugin
{
    public function enter(Array_ $array, Context $context): ?FuncCall
    {
        foreach ($context->parents as $parent) {
            if ($parent instanceof Array_) {
                continue;
            }
            if ($parent instanceof List_) {
                return null;
            }
            /** @var string */
            $key = $parent->getAttribute('currentNode');
            if ($parent instanceof Assign && $key === 'var') {
                return null;
            }
            if ($parent instanceof Foreach_ && $key === 'valueVar') {
                return null;
            }
            break;
        }
        $hasUnpack = false;
        foreach ($array->items as $item) {
            if (!$item) {
                return null;
            }
            if ($item->unpack) {
                $hasUnpack = true;
                break;
            }
        }
        if (!$hasUnpack) {
            return null;
        }
        $args = [];
        $current = new Array_();
        /** @var ArrayItem */
        foreach ($array->items as $item) {
            if ($item->unpack) {
                if ($current->items) {
                    $args []= new Arg($current);
                    $current = new Array_();
                }
                $args []= new Arg($item->value);
            } else {
                $current->items []= $item;
            }
        }
        if ($current->items) {
            $args []= new Arg($current);
        }
        return Plugin::call("array_merge", ...$args);
    }
}
