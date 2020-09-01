<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;

class NullableType extends Plugin
{
    /**
     * Remove nullable typehint.
     *
     * @return array
     */
    public static function runAfter(): array
    {
        return [
            TypeHintStripper::class => [
                'nullable' => true
            ]
        ];
    }
}
