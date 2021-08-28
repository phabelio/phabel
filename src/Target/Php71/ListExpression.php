<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\ListSplitter;

/**
 * Polyfills list expression return value.
 */
class ListExpression extends Plugin
{
    public static function previous(array $config)
    {
        $phabelReturn = [ListSplitter::class => ['parentExpr' => true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
