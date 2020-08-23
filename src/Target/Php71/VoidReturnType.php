<?php

namespace Spatie\Php7to5\NodeVisitors;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;

class VoidReturnType extends Plugin
{
    /**
     * Remove void return typehint.
     *
     * @return array
     */
    public static function runAfter(): array
    {
        return [
            TypeHintStripper::class => [
                'void' => true
            ]
        ];
    }
}
