<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\Promise;
use Phabel\Amp\Success;
use Phabel\Amp\Sync\ThreadedMutex;
use function Phabel\Amp\call;
/**
 * A thread-safe container that shares a value between multiple threads.
 *
 * @deprecated ext-pthreads development has been halted, see https://github.com/krakjoe/pthreads/issues/929
 */
final class ThreadedParcel implements Parcel
{
    /** @var ThreadedMutex */
    private $mutex;
    /** @var \Threaded */
    private $storage;
    /**
     * Creates a new shared object container.
     *
     * @param mixed $value The value to store in the container.
     */
    public function __construct($value)
    {
        $this->mutex = new ThreadedMutex();
        $this->storage = new Internal\ParcelStorage($value);
    }
    /**
     * {@inheritdoc}
     */
    public function unwrap()
    {
        $phabelReturn = new Success($this->storage->get());
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return \Amp\Promise
     */
    public function synchronized(callable $callback)
    {
        $phabelReturn = call(function () use($callback) {
            /** @var \Amp\Sync\Lock $lock */
            $lock = (yield $this->mutex->acquire());
            try {
                $result = (yield call($callback, $this->storage->get()));
                if ($result !== null) {
                    $this->storage->set($result);
                }
            } finally {
                $lock->release();
            }
            return $result;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
