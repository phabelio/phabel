<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\CallableMaker;
use Phabel\Amp\Deferred;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
class LocalMutex implements Mutex
{
    use CallableMaker;
    // kept for BC only
    /** @var bool */
    private $locked = \false;
    /** @var Deferred[] */
    private $queue = [];
    /** {@inheritdoc} */
    public function acquire() : Promise
    {
        if (!$this->locked) {
            $this->locked = \true;
            return new Success(new Lock(0, \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
        }
        $this->queue[] = $deferred = new Deferred();
        return $deferred->promise();
    }
    private function release()
    {
        if (!empty($this->queue)) {
            $deferred = \array_shift($this->queue);
            $deferred->resolve(new Lock(0, \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
            return;
        }
        $this->locked = \false;
    }
}
