<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Replace static and mixed typehint.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StaticMixedReplacer extends Plugin
{
    public static function previous(array $config): array
    {
        return [TypeHintReplacer::class => ['types' => ['static', 'mixed']]];
    }
}
