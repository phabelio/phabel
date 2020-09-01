<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Target\Php71\ArrayList;
use Phabel\Target\Php71\ClassConstantVisibilityModifiersRemover;
use Phabel\Target\Php71\IssetExpressionFixer;
use Phabel\Target\Php71\ListKey;
use Phabel\Target\Php71\MultipleCatchReplacer;
use Phabel\Target\Php71\NestedExpressionFixer;
use Phabel\Target\Php71\NullableType;
use Phabel\Target\Php71\VoidReturnType;

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
        return ['symfony/polyfill-php71' => '*'];
    }
    public static function runWithAfter(): array
    {
        return [
            IssetExpressionFixer::class,
            NestedExpressionFixer::class,
            ArrayList::class,
            ClassConstantVisibilityModifiersRemover::class,
            ListKey::class,
            MultipleCatchReplacer::class,
            VoidReturnType::class,
            NullableType::class
        ];
    }
}
