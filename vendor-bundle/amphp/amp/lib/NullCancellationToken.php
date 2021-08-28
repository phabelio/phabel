<?php

namespace Phabel\Amp;

/**
 * A NullCancellationToken can be used to avoid conditionals to check whether a token has been provided.
 *
 * Instead of writing
 *
 * ```php
 * if ($token) {
 *     $token->throwIfRequested();
 * }
 * ```
 *
 * potentially multiple times, it allows writing
 *
 * ```php
 * $token = $token ?? new NullCancellationToken;
 *
 * // ...
 *
 * $token->throwIfRequested();
 * ```
 *
 * instead.
 */
final class NullCancellationToken implements CancellationToken
{
    /** @inheritdoc */
    public function subscribe(callable $callback)
    {
        $phabelReturn = "null-token";
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function unsubscribe($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        // nothing to do
    }
    /** @inheritdoc */
    public function isRequested()
    {
        $phabelReturn = \false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function throwIfRequested()
    {
        // nothing to do
    }
}
