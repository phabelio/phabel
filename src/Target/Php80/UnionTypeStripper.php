<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;
/**
 * Strip union types, polyfilling type checks.
 */
class UnionTypeStripper extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function previous(array $config)
    {
        $phabelReturn = [TypeHintReplacer::class => ['union' => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
