<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Strip union types, polyfilling type checks.
 */
class UnionTypeStripper extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function previous(array $config): array
    {
        return [TypeHintReplacer::class => ['union' => true]];
    }
}
