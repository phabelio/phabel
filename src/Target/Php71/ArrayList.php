<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Replaces [] array list syntax.
 */
class ArrayList extends Plugin
{
    /**
     * Called when entering Foreach_ node.
     *
     * @param Foreach_ $node Node
     *
     * @return void
     */
    public function enterForeach(Foreach_ $node): void
    {
        if ($node->valueVar instanceof Array_) {
            self::replaceTypeInPlace($node->valueVar, List_::class);
        }
    }
    /**
     * Called when entering list for nested lists.
     *
     * @param List_ $node Node
     *
     * @return void
     */
    public function enterList(List_ $node): void
    {
        foreach ($node->items as $item) {
            if ($item && $item->value instanceof Array_) {
                self::replaceTypeInPlace($item->value, List_::class);
            }
        }
    }
}
