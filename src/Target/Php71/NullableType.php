<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullableType extends Plugin
{
    /**
     * Remove nullable typehint.
     *
     * @return array
     */
    public static function runAfter(array $config): array
    {
        return [
            TypeHintReplacer::class => [
                'nullable' => true
            ]
        ];
    }
}
