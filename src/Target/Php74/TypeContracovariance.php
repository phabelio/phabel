<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Target\Php74\TypeContracovariance\TypeContracovariance as T;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContracovariance extends Plugin
{
    /**
     *
     */
    public static function previous(array $config): array
    {
        return [ClassStoragePlugin::class => [T::class => true]];
    }
}
