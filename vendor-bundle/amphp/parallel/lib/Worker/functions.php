<?php

namespace Phabel\Amp\Parallel\Worker;

use Phabel\Amp\Loop;
use Phabel\Amp\Promise;
const LOOP_POOL_IDENTIFIER = Pool::class;
const LOOP_FACTORY_IDENTIFIER = WorkerFactory::class;
/**
 * Gets or sets the global worker pool.
 *
 * @param Pool|null $pool A worker pool instance.
 *
 * @return Pool The global worker pool instance.
 */
function pool(Pool $pool = null)
{
    if ($pool === null) {
        $pool = Loop::getState(LOOP_POOL_IDENTIFIER);
        if ($pool) {
            $phabelReturn = $pool;
            if (!$phabelReturn instanceof Pool) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Pool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $pool = new DefaultPool();
    }
    Loop::setState(LOOP_POOL_IDENTIFIER, $pool);
    $phabelReturn = $pool;
    if (!$phabelReturn instanceof Pool) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Pool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Enqueues a task to be executed by the global worker pool.
 *
 * @param Task $task The task to enqueue.
 *
 * @return Promise<mixed>
 */
function enqueue(Task $task)
{
    $phabelReturn = pool()->enqueue($task);
    if (!$phabelReturn instanceof Promise) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Enqueues a callable to be executed by the global worker pool.
 *
 * @param callable $callable Callable needs to be serializable.
 * @param mixed    ...$args Arguments have to be serializable.
 *
 * @return Promise<mixed>
 */
function enqueueCallable(callable $callable, ...$args)
{
    return enqueue(new CallableTask($callable, $args));
}
/**
 * Gets a worker from the global worker pool.
 *
 * @return \Amp\Parallel\Worker\Worker
 */
function worker()
{
    $phabelReturn = pool()->getWorker();
    if (!$phabelReturn instanceof Worker) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Worker, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Creates a worker using the global worker factory.
 *
 * @return \Amp\Parallel\Worker\Worker
 */
function create()
{
    $phabelReturn = factory()->create();
    if (!$phabelReturn instanceof Worker) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type Worker, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
/**
 * Gets or sets the global worker factory.
 *
 * @param WorkerFactory|null $factory
 *
 * @return WorkerFactory
 */
function factory(WorkerFactory $factory = null)
{
    if ($factory === null) {
        $factory = Loop::getState(LOOP_FACTORY_IDENTIFIER);
        if ($factory) {
            $phabelReturn = $factory;
            if (!$phabelReturn instanceof WorkerFactory) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type WorkerFactory, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $factory = new DefaultWorkerFactory();
    }
    Loop::setState(LOOP_FACTORY_IDENTIFIER, $factory);
    $phabelReturn = $factory;
    if (!$phabelReturn instanceof WorkerFactory) {
        throw new \TypeError(__METHOD__ . '(): Return value must be of type WorkerFactory, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    return $phabelReturn;
}
