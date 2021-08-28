<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\CallableMaker;
use Phabel\Amp\Deferred;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
class LocalSemaphore implements Semaphore
{
    use CallableMaker;
    // kept for BC only
    /** @var int[] */
    private $locks;
    /** @var Deferred[] */
    private $queue = [];
    public function __construct($maxLocks)
    {
        if (!\is_int($maxLocks)) {
            if (!(\is_bool($maxLocks) || \is_numeric($maxLocks))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($maxLocks) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($maxLocks) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $maxLocks = (int) $maxLocks;
            }
        }
        if ($maxLocks < 1) {
            throw new \Error('The number of locks must be greater than 0');
        }
        $this->locks = \range(0, $maxLocks - 1);
    }
    /** {@inheritdoc} */
    public function acquire()
    {
        if (!empty($this->locks)) {
            $phabelReturn = new Success(new Lock(\array_shift($this->locks), \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $this->queue[] = $deferred = new Deferred();
        $phabelReturn = $deferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function release(Lock $lock)
    {
        $id = $lock->getId();
        if (!empty($this->queue)) {
            $deferred = \array_shift($this->queue);
            $deferred->resolve(new Lock($id, \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
            return;
        }
        $this->locks[] = $id;
    }
}
