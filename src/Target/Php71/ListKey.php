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
    public static function runWithAfter(array $config): array
    {
        return [ListSplitter::class => ['key' => true]];
    }
}
