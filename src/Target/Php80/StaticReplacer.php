<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;

/**
 * Replace static typehint.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class StaticReplacer extends Plugin
{
    public static function previous(array $config)
    {
        $phabelReturn = [TypeHintReplacer::class => ['types' => ['static']]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
