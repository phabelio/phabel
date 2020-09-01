<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\Target\Php70\AnonymousClassReplacer;
use Phabel\Target\Php70\ClosureCallReplacer;
use Phabel\Target\Php70\CompoundAccess;
use Phabel\Target\Php70\DefineArrayReplacer;
use Phabel\Target\Php70\GroupUseReplacer;
use Phabel\Target\Php70\IssetExpressionFixer;
use Phabel\Target\Php70\NestedExpressionFixer;
use Phabel\Target\Php70\NullCoalesceReplacer;
use Phabel\Target\Php70\ReservedNameReplacer;
use Phabel\Target\Php70\ScalarTypeHintsRemover;
use Phabel\Target\Php70\SpaceshipOperatorReplacer;
use Phabel\Target\Php70\StrictTypesDeclareStatementRemover;
use Phabel\Target\Php70\ThrowableReplacer;
use Phabel\Target\Php70\YieldFromReturnDetector;

/**
 * Makes changes necessary to polyfill PHP 7.0 and run on PHP 5.6 and below.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Php70 extends Plugin
{
    public static function composerRequires(): array
    {
        return ['symfony/polyfill-php70' => '*'];
    }
    public static function runWithAfter(): array
    {
        return [
            IssetExpressionFixer::class,
            NestedExpressionFixer::class,
            AnonymousClassReplacer::class,
            ClosureCallReplacer::class,
            CompoundAccess::class,
            DefineArrayReplacer::class,
            GroupUseReplacer::class,
            NullCoalesceReplacer::class,
            ReservedNameReplacer::class,
            ScalarTypeHintsRemover::class,
            SpaceshipOperatorReplacer::class,
            StrictTypesDeclareStatementRemover::class,
            ThrowableReplacer::class,
            YieldFromReturnDetector::class
        ];
    }
}
