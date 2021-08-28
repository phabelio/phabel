<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
class SemaphoreMutex implements Mutex
{
    /** @var Semaphore */
    private $semaphore;
    /**
     * @param Semaphore $semaphore A semaphore with a single lock.
     */
    public function __construct(Semaphore $semaphore)
    {
        $this->semaphore = $semaphore;
    }
    /** {@inheritdoc} */
    public function acquire()
    {
        $phabelReturn = call(function () {
            /** @var \Amp\Sync\Lock $lock */
            $lock = (yield $this->semaphore->acquire());
            if ($lock->getId() !== 0) {
                $lock->release();
                throw new \Error("Cannot use a semaphore with more than a single lock");
            }
            return $lock;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
