<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
/**
 * A thread-safe, asynchronous mutex using the pthreads locking mechanism.
 *
 * Compatible with POSIX systems and Microsoft Windows.
 *
 * @deprecated ext-pthreads development has been halted, see https://github.com/krakjoe/pthreads/issues/929
 */
class ThreadedMutex implements Mutex
{
    /** @var Internal\MutexStorage */
    private $mutex;
    /**
     * Creates a new threaded mutex.
     */
    public function __construct()
    {
        $this->mutex = new Internal\MutexStorage();
    }
    /**
     * {@inheritdoc}
     */
    public function acquire()
    {
        $phabelReturn = $this->mutex->acquire();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
