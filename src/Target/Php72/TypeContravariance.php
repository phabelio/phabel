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
    public static function previous(array $config)
    {
        $phabelReturn = [ClassStoragePlugin::class => [T::class => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
