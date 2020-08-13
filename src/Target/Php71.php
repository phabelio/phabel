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
use Phabel\Target\Php71\ArrayList;
use Phabel\Target\Php71\ClassConstantVisibilityModifiersRemover;
use Phabel\Target\Php71\ListKey;
use Phabel\Target\Php71\MultipleCatchReplacer;
use Spatie\Php7to5\NodeVisitors\NullableTypeRemover;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 */
class Php71 extends Plugin
{
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
