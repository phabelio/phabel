<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Deferred;
use Phabel\Amp\Promise;
/**
 * A barrier is a synchronization primitive.
 *
 * The barrier is initialized with a certain count, which can be increased and decreased until it reaches zero.
 *
 * A count of one can be used to block multiple coroutines until a certain condition is met.
 *
 * A count of N can be used to await multiple coroutines doing an action to complete.
 *
 * **Example**
 *
 * ```php
 * $barrier = new Amp\Sync\Barrier(2);
 * $barrier->arrive();
 * $barrier->arrive(); // promise returned from Barrier::await() is now resolved
 *
 * yield $barrier->await();
 * ```
 */
final class Barrier
{
    /** @var int */
    private $count;
    /** @var Deferred */
    private $deferred;
    public function __construct($count)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $count = (int) $count;
            }
        }
        if ($count < 1) {
            throw new \Error('Count must be positive, got ' . $count);
        }
        $this->count = $count;
        $this->deferred = new Deferred();
    }
    public function getCount()
    {
        $phabelReturn = $this->count;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function arrive($count = 1)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $count = (int) $count;
            }
        }
        if ($count < 1) {
            throw new \Error('Count must be at least 1, got ' . $count);
        }
        if ($count > $this->count) {
            throw new \Error('Count cannot be greater than remaining count: ' . $count . ' > ' . $this->count);
        }
        $this->count -= $count;
        if ($this->count === 0) {
            $this->deferred->resolve();
        }
    }
    public function register($count = 1)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $count = (int) $count;
            }
        }
        if ($count < 1) {
            throw new \Error('Count must be at least 1, got ' . $count);
        }
        if ($this->count === 0) {
            throw new \Error('Can\'t increase count, because the barrier already broke');
        }
        $this->count += $count;
    }
    public function await()
    {
        $phabelReturn = $this->deferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
