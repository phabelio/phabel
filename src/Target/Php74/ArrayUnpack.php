<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;

class ArrayUnpack extends Plugin
{
    public function enter(Array_ $array): ?FuncCall
    {
        $hasUnpack = false;
        foreach ($array->items as $item) {
            if ($item->unpack) {
                $hasUnpack = true;
                break;
            }
        }
        if (!$hasUnpack) {
            return;
        }
        $args = [];
        $array = new Array_();
        foreach ($array->items as $item) {
            if ($item->unpack) {
                if ($array->items) {
                    $args []= new Arg($array);
                    $array = new Array_();
                }
                $args []= new Arg($item->value);
            } else {
                $array->items []= $item;
            }
        }
        if ($array->items) {
            $args []= new Arg($array);
        }
        return Plugin::call("array_merge", ...$args);
    }
}
