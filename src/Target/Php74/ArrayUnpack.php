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
    public function enter(Array_ $array, Context $context)
    {
        foreach ($context->parents as $parent) {
            if ($parent instanceof Array_) {
                continue;
            }
            if ($parent instanceof List_) {
                $phabelReturn = null;
                if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            /** @var string */
            $key = $parent->getAttribute('currentNode');
            if ($parent instanceof Assign && $key === 'var') {
                $phabelReturn = null;
                if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            if ($parent instanceof Foreach_ && $key === 'valueVar') {
                $phabelReturn = null;
                if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            break;
        }
        $hasUnpack = false;
        foreach ($array->items as $item) {
            if (!$item) {
                $phabelReturn = null;
                if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            if ($item->unpack) {
                $hasUnpack = true;
                break;
            }
        }
        if (!$hasUnpack) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $args = [];
        $current = new Array_();
        /** @var ArrayItem */
        foreach ($array->items as $item) {
            if ($item->unpack) {
                if ($current->items) {
                    $args[] = new Arg($current);
                    $current = new Array_();
                }
                $args[] = new Arg($item->value);
            } else {
                $current->items[] = $item;
            }
        }
        if ($current->items) {
            $args[] = new Arg($current);
        }
        $phabelReturn = Plugin::call("array_merge", ...$args);
        if (!($phabelReturn instanceof FuncCall || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?FuncCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
