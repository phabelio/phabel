<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin\ListSplitter;

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
