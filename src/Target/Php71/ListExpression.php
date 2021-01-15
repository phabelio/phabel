<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Plugin\ListSplitter;

/**
 * Polyfills list expression return value.
 */
class ListExpression extends Plugin
{
    public static function next(array $config): array
    {
        return [ListSplitter::class => ['parentExpr' => true]];
    }
}
