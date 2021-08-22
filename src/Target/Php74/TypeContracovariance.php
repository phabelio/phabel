<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePluginCrawler;
use Phabel\Target\Php74\TypeContracovariance\TypeContracovariance as T;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContracovariance extends Plugin
{
    public static function previous(array $config): array
    {
        return [ClassStoragePluginCrawler::class => [T::class => true]];
    }
}
