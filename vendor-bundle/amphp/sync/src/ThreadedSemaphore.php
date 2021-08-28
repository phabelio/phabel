<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
/**
 * An asynchronous semaphore based on pthreads' synchronization methods.
 *
 * This is an implementation of a thread-safe semaphore that has non-blocking
 * acquire methods. There is a small tradeoff for asynchronous semaphores; you
 * may not acquire a lock immediately when one is available and there may be a
 * small delay. However, the small delay will not block the thread.
 *
 * @deprecated ext-pthreads development has been halted, see https://github.com/krakjoe/pthreads/issues/929
 */
class ThreadedSemaphore implements Semaphore
{
    /** @var \Threaded */
    private $semaphore;
    /**
     * Creates a new semaphore with a given number of locks.
     *
     * @param int $locks The maximum number of locks that can be acquired from the semaphore.
     */
    public function __construct($locks)
    {
        if (!\is_int($locks)) {
            if (!(\is_bool($locks) || \is_numeric($locks))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($locks) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($locks) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $locks = (int) $locks;
            }
        }
        if ($locks < 1) {
            throw new \Error("The number of locks should be a positive integer");
        }
        $this->semaphore = new Internal\SemaphoreStorage($locks);
    }
    /**
     * {@inheritdoc}
     */
    public function acquire()
    {
        $phabelReturn = $this->semaphore->acquire();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
