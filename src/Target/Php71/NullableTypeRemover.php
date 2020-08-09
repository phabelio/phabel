<?php

namespace Spatie\Php7to5\NodeVisitors;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;

class NullableTypeRemover extends Plugin
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
                'nulable' => true
            ]
        ];
    }
}
