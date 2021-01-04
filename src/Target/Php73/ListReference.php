<?php

namespace Phabel\Target\Php73;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ListSplitter;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills list assignment by reference.
 */
class ListReference extends ListSplitter
{
    /**
     * Whether this is a referenced list.
     *
     * @param List_|Array_ $list    List
     * @param bool         $recurse Whether to recurse while checking
     *
     * @return boolean
     */
    protected function shouldSplit($list, bool $recurse = false): bool
    {
        /** @var ArrayItem $item */
        foreach ($list->items ?? [] as $item) {
            if (!$item) {
                continue;
            }
            if ($item->byRef) {
                return true;
            } elseif ($recurse && ($item->value instanceof List_ || $item->value instanceof Array_)) {
                if ($this->shouldSplit($item->value, $recurse)) {
                    return true;
                }
            }
        }
        return false;
    }
}
