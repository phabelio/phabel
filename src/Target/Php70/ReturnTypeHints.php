<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\TypeHintReplacer;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReturnTypeHints extends Plugin
{
    public static function previous(array $config)
    {
        $phabelReturn = [TypeHintReplacer::class => ['return' => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
