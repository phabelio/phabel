<?php

namespace Phabel\Target\Php72;

use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Target\Php72\TypeWideningRestriction\TypeWideningRestriction as T;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeWideningRestriction extends Plugin
{
    public static function previous(array $config): array
    {
        return ClassStoragePlugin::enable(T::class);
    }
}
