<?php

namespace Phabel\Target\Php72;

use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Target\Php72\TypeContravariance\TypeContravariance as T;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContravariance extends Plugin
{
    public static function previous(array $config): array
    {
        return [ClassStoragePlugin::class => [T::class => true]];
    }
}
