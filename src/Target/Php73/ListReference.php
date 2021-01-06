<?php

namespace Phabel\Target\Php73;

use Phabel\Plugin\ListSplitter;

/**
 * Polyfills list assignment by reference.
 */
class ListReference extends ListSplitter
{
    public static function runWithAfter(array $config): array
    {
        return [ListSplitter::class => ['byRef' => true]];
    }
}
