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
    public static function array_filter(array $array, $callback = null, int $mode = 0): array
    {
        if (!(\is_callable($callback) || \is_null($callback))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($callback) must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($callback) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $callback = $callback ?? function ($v) {
            return $v;
        };
        return \array_filter($array, $callback, $mode);
    }
    public static function array_splice(array &$array, int $offset, $length = null, $replacement = []): array
    {
        if (!true) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($replacement) must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($replacement) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        $length = $length ?? \max(\count($array) - $offset, 0);
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
