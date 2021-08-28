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
    public static function array_filter(array $array, $callback = null, $mode = 0)
    {
        if (!(\is_callable($callback) || \is_null($callback))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($callback) must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($callback) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($mode)) {
            if (!(\is_bool($mode) || \is_numeric($mode))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($mode) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($mode) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $mode = (int) $mode;
        }
        $callback = isset($callback) ? $callback : function ($v) {
            return $v;
        };
        $phabelReturn = \array_filter($array, $callback, $mode);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function array_splice(array &$array, $offset, $length = null, mixed $replacement = [])
    {
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        $length = isset($length) ? $length : \max(\count($array) - $offset, 0);
        $phabelReturn = \array_splice($array, $offset, $length, $replacement);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config)
    {
        $phabelReturn = [TargetPolyfill::class => [self::class => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
