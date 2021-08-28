<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
/**
 * Invokes the given callback while maintaining a lock from the provided mutex. The lock is automatically released after
 * invoking the callback or once the promise returned by the callback is resolved. If the callback returns a Generator,
 * it will be run as a coroutine. See Amp\call().
 *
 * @param Mutex    $mutex
 * @param callable $callback
 * @param array    ...$args
 *
 * @return Promise Resolves with the return value of the callback.
 */
function synchronized(Mutex $mutex, callable $callback, ...$args)
{
    $phabelReturn = call(static function () use($mutex, $callback, $args) {
        /** @var Lock $lock */
        $lock = (yield $mutex->acquire());
        try {
            return (yield call($callback, ...$args));
        } finally {
            $lock->release();
        }
    });
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
