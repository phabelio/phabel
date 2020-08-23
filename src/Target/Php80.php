<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\UnionTypeStripper;

/**
 * Makes changes necessary to polyfill PHP 8.0 and run on PHP 7.4 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php80 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php80' => '*'];
    }

    public static function runWithAfter(): array
    {
        return [
            UnionTypeStripper::class,
        ];
    }
}
