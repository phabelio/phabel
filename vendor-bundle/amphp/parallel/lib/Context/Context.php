<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Parallel\Sync\Channel;
use Phabel\Amp\Promise;
interface Context extends Channel
{
    /**
     * @return bool
     */
    public function isRunning();
    /**
     * Starts the execution context.
     *
     * @return Promise<null> Resolved once the context has started.
     */
    public function start();
    /**
     * Immediately kills the context.
     */
    public function kill();
    /**
     * @return \Amp\Promise<mixed> Resolves with the returned from the context.
     *
     * @throws \Amp\Parallel\Context\ContextException If the context dies unexpectedly.
     * @throws \Amp\Parallel\Sync\PanicError If the context throws an uncaught exception.
     */
    public function join();
}
