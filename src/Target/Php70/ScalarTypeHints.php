<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ScalarTypeHints extends Plugin
{
    public static function runAfter(array $config): array
    {
        return [
            TypeHintReplacer::class => [
                'types' => ['int', 'float', 'string', 'bool']
            ]
        ];
    }
}
