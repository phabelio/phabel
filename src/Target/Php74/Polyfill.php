<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Target\Polyfill as TargetPolyfill;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    // Todo: gmmktime, mktime

    public static function array_filter(array $array, ?callable $callback = null, int $mode = 0): array
    {
        $callback ??= fn ($v) => $v;
        return \array_filter($array, $callback, $mode);
    }

    public static function array_splice(
        array &$array,
        int $offset,
        ?int $length = null,
        mixed $replacement = []
    ): array {
        $length ??= \max(\count($array) - $offset, 0);
        return \array_splice($array, $offset, $length, $replacement);
    }

    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config): array
    {
        return [TargetPolyfill::class => [self::class => true]];
    }
}
