<?php

namespace Phabel\Target\Php72;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;

class ObjectTypeHintReplacer extends Plugin
{
    /**
     * Alias.
     *
     * @return array
     */
    public static function runAfter(): array
    {
        return [
            TypeHintStripper::class => [
                'types' => ['object']
            ]
        ];
    }
}
