<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Target\Php80\ConstantReplacer\ConstantReplacer as T;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ConstantReplacer extends Plugin
{
    public static function previous(array $config) : array
    {
        return [ClassStoragePlugin::class => [T::class => $config]];
    }
}
