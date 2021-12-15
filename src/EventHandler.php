<?php

namespace Phabel;

abstract class EventHandler implements \Phabel\EventHandlerInterface
{
    /**
     *
     */
    public function onStart() : void
    {
    }
    /**
     *
     */
    public function onBeginPluginGraphResolution() : void
    {
    }
    /**
     *
     */
    public function onEndPluginGraphResolution() : void
    {
    }
    /**
     *
     */
    public function onBeginDirectoryTraversal(int $total, int $workers) : void
    {
    }
    /**
     *
     */
    public function onBeginAstTraversal(string $file) : void
    {
    }
    /**
     * @param (int | \Throwable) $iterationsOrError
     */
    public function onEndAstTraversal(string $file, $iterationsOrError) : void
    {
        if (!$iterationsOrError instanceof \Throwable) {
            if (!\is_int($iterationsOrError)) {
                if (!(\is_bool($iterationsOrError) || \is_numeric($iterationsOrError))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($iterationsOrError) must be of type Throwable|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($iterationsOrError) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $iterationsOrError = (int) $iterationsOrError;
            }
        }
    }
    /**
     *
     */
    public function onEndDirectoryTraversal() : void
    {
    }
    /**
     *
     */
    public function onBeginClassGraphMerge(int $count) : void
    {
    }
    /**
     *
     */
    public function onClassGraphMerged() : void
    {
    }
    /**
     *
     */
    public function onEndClassGraphMerge() : void
    {
    }
    /**
     *
     */
    public function onEnd() : void
    {
    }
}
