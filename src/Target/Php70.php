<?php

namespace Phabel\Target;

use Phabel\Plugin;
use Phabel\PluginInterface;
use Phabel\Target\Php70\AnonymousClassReplacer;
use Phabel\Target\Php70\ClosureCallReplacer;
use Phabel\Target\Php70\CompoundAccess;
use Phabel\Target\Php70\DefineArrayReplacer;
use Phabel\Target\Php70\GroupUseReplacer;
use Phabel\Target\Php70\NullCoalesceReplacer;
use Phabel\Target\Php70\ReservedNameReplacer;
use Phabel\Target\Php70\ScalarTypeHintsRemover;
use Phabel\Target\Php70\SpaceshipOperatorReplacer;
use Phabel\Target\Php70\StrictTypesDeclareStatementRemover;
use Phabel\Target\Php70\ThrowableReplacer;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 */
class Php70 extends Plugin
{
    public static function runWithAfter(): array
    {
        return [
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
            ThrowableReplacer::class
        ];
    }
}
