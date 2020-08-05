<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintStripper;

class ScalarTypeHintsRemover extends Plugin
{
    /**
     * Alias.
     *
     * @return array
     */
    public static function needs(): array
    {
        return [
            TypeHintStripper::class => [
                'types' => ['int', 'integer', 'float', 'string', 'bool', 'boolean']
            ]
        ];
    }
}
