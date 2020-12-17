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
     * Run with before.
     *
     * @return array
     */
    public static function runWithAfter(array $config): array
    {
        return [
            TypeHintReplacer::class => [
                'union' => true
            ]
        ];
    }
}
