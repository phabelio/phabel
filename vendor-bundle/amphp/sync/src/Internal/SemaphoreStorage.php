<?php

namespace Phabel\Amp\Sync\Internal;

use Phabel\Amp\Delayed;
use Phabel\Amp\Promise;
use Phabel\Amp\Sync\Lock;
use function Phabel\Amp\call;
/** @internal */
final class SemaphoreStorage extends \Threaded
{
    const LATENCY_TIMEOUT = 10;
    /**
     * Creates a new semaphore with a given number of locks.
     *
     * @param int $locks The maximum number of locks that can be acquired from the semaphore.
     */
    public function __construct(int $locks)
    {
        foreach (\range(0, $locks - 1) as $lock) {
            $this[] = $lock;
        }
    }
    public function acquire() : Promise
    {
        /**
         * Uses a double locking mechanism to acquire a lock without blocking. A
         * synchronous mutex is used to make sure that the semaphore is queried one
         * at a time to preserve the integrity of the semaphore itself. Then a lock
         * count is used to check if a lock is available without blocking.
         *
         * If a lock is not available, we add the request to a queue and set a timer
         * to check again in the future.
         */
        return call(function () : \Generator {
            $tsl = function () {
                // If there are no locks available or the wait queue is not empty,
                // we need to wait our turn to acquire a lock.
                if (!$this->count()) {
                    $phabelReturn = null;
                    if (!\is_null($phabelReturn)) {
                        if (!\is_int($phabelReturn)) {
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            } else {
                                $phabelReturn = (int) $phabelReturn;
                            }
                        }
                    }
                    return $phabelReturn;
                }
                $phabelReturn = $this->shift();
                if (!\is_null($phabelReturn)) {
                    if (!\is_int($phabelReturn)) {
                        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $phabelReturn = (int) $phabelReturn;
                        }
                    }
                }
                return $phabelReturn;
            };
            while (!$this->count() || ($id = $this->synchronized($tsl)) === null) {
                (yield new Delayed(self::LATENCY_TIMEOUT));
            }
            return new Lock($id, function (Lock $lock) {
                $id = $lock->getId();
                $this->synchronized(function () use($id) {
                    $this[] = $id;
                });
            });
        });
    }
}
