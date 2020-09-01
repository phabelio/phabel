<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Target\Php74\ArrayUnpack;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Target\Php74\IssetExpressionFixer;
use Phabel\Target\Php74\NestedExpressionFixer;
use Phabel\Target\Php74\NullCoalesceAssignment;
use Phabel\Target\Php74\TypedProperty;

/**
 * Makes changes necessary to polyfill PHP 7.4 and run on PHP 7.3 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php74 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php74' => '*'];
    }
    public static function runWithAfter(): array
    {
        return [
            IssetExpressionFixer::class,
            NestedExpressionFixer::class,
            ArrayUnpack::class,
            ArrowClosure::class,
            NullCoalesceAssignment::class,
            TypedProperty::class
        ];
    }
}
