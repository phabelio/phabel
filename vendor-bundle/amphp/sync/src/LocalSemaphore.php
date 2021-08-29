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
    public function __construct(int $maxLocks)
    {
        if ($maxLocks < 1) {
            throw new \Error('The number of locks must be greater than 0');
        }
        $this->locks = \range(0, $maxLocks - 1);
    }
    /** {@inheritdoc} */
    public function acquire() : Promise
    {
        if (!empty($this->locks)) {
            return new Success(new Lock(\array_shift($this->locks), \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
        }
        $this->queue[] = $deferred = new Deferred();
        return $deferred->promise();
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
