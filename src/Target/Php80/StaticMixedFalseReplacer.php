<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Replace static, mixed and false typehints.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StaticMixedFalseReplacer extends Plugin
{
    public static function previous(array $config): array
    {
        return [
            TypeHintReplacer::class => [
                'types' => ['static', 'mixed', 'false']
            ]
        ];
    }
}
