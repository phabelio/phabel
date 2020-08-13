<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Target\Php71\ArrayList;
use Phabel\Target\Php71\ClassConstantVisibilityModifiersRemover;
use Phabel\Target\Php71\ListKey;
use Phabel\Target\Php71\MultipleCatchReplacer;
use Spatie\Php7to5\NodeVisitors\NullableTypeRemover;

/**
 * Makes changes necessary to polyfill PHP 7.1 and run on PHP 7.0 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php71 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php70' => '*'];
    }
    public static function runWithAfter(): array
    {
        return [
            ArrayList::class,
            ClassConstantVisibilityModifiersRemover::class,
            ListKey::class,
            MultipleCatchReplacer::class,
            NullableTypeRemover::class
        ];
    }
}
