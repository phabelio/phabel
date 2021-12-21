<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Remove nullable typehint.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullableType extends Plugin
{
    public static function previous(array $config): array
    {
        return [TypeHintReplacer::class => ['nullable' => true]];
    }
}
