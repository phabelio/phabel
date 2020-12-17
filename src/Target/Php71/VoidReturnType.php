<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class VoidReturnType extends Plugin
{
    /**
     * Remove void return typehint.
     *
     * @return array
     */
    public static function runAfter(array $config): array
    {
        return [
            TypeHintReplacer::class => [
                'void' => true
            ]
        ];
    }
}
