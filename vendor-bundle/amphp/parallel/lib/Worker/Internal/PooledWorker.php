<?php

namespace Phabel\Amp\Parallel\Worker\Internal;

use Phabel\Amp\Parallel\Worker\Task;
use Phabel\Amp\Parallel\Worker\Worker;
use Phabel\Amp\Promise;
/** @internal */
final class PooledWorker implements Worker
{
    /** @var callable */
    private $push;
    /** @var Worker */
    private $worker;
    /**
     * @param Worker $worker
     * @param callable $push Callable to push the worker back into the queue.
     */
    public function __construct(Worker $worker, callable $push)
    {
        $this->worker = $worker;
        $this->push = $push;
    }
    /**
     * Automatically pushes the worker back into the queue.
     */
    public function __destruct()
    {
        $phabel_add02d4737e85587 = $this->push;
        $phabel_add02d4737e85587($this->worker);
    }
    /**
     * {@inheritdoc}
     */
    public function isRunning()
    {
        $phabelReturn = $this->worker->isRunning();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function isIdle()
    {
        $phabelReturn = $this->worker->isIdle();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function enqueue(Task $task)
    {
        $phabelReturn = $this->worker->enqueue($task);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function shutdown()
    {
        $phabelReturn = $this->worker->shutdown();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritdoc}
     */
    public function kill()
    {
        $this->worker->kill();
    }
}
