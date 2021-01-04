<?php

namespace Phabel\Target\Php71;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\ListSplitter;
use Phabel\Target\Php73\ListReference;
use PhpParser\BuilderHelpers;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Foreach_;

/**
 * Polyfills keyed list assignment.
 */
class ListKey extends ListSplitter
{
    /**
     * Whether this is a keyed list.
     *
     * @param List_|Array_ $list List
     *
     * @return boolean
     */
    protected function shouldSplit($list, bool $recurse = false): bool
    {
        return isset($list->items[0]->key);
    }
    /**
     * {@inheritDoc}
     */
    public static function runAfter(array $config): array
    {
        return [ArrayList::class];
    }
}
