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
    public function acquire()
    {
        if (!$this->locked) {
            $this->locked = \true;
            $phabelReturn = new Success(new Lock(0, \Phabel\Target\Php71\ClosureFromCallable::fromCallable([$this, 'release'])));
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
