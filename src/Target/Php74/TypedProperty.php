<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Implement typed properties.
 */
class TypedProperty extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function previous(array $config): array
    {
        return [TypeHintReplacer::class => ['property' => true]];
    }
}
