<?php

namespace Phabel;

use Phabel\Plugin\TypeHintStripper;

/**
 * Strip union types, polyfilling type checks.
 */
class UnionTypeStripper extends Plugin
{
    /**
     * Run with before.
     *
     * @return array
     */
    public static function runWithAfter(): array
    {
        return [
            TypeHintStripper::class => [
                'union' => true
            ]
        ];
    }
}
