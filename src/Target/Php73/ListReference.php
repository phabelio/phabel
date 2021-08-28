<?php

namespace Phabel\Target\Php73;

use Phabel\Plugin;
use Phabel\Plugin\ListSplitter;
/**
 * Polyfills list assignment by reference.
 */
class ListReference extends Plugin
{
    public static function previous(array $config)
    {
        $phabelReturn = [ListSplitter::class => ['byRef' => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
