<?php

namespace Phabel\Amp\Parallel\Context;

use Phabel\Amp\Promise;
class DefaultContextFactory implements ContextFactory
{
    public function create($script)
    {
        /**
         * Creates a thread if ext-parallel is installed, otherwise creates a child process.
         *
         * @inheritdoc
         */
        if (Parallel::isSupported()) {
            $phabelReturn = new Parallel($script);
            if (!$phabelReturn instanceof Context) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Context, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = new Process($script);
        if (!$phabelReturn instanceof Context) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Context, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates and starts a thread if ext-parallel is installed, otherwise creates a child process.
     *
     * @inheritdoc
     */
    public function run($script)
    {
        if (Parallel::isSupported()) {
            $phabelReturn = Parallel::run($script);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = Process::run($script);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
