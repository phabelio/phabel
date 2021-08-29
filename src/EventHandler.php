<?php

namespace Phabel;

abstract class EventHandler implements EventHandlerInterface
{
    public function onStart()
    {
    }
    public function onBeginPluginGraphResolution()
    {
    }
    public function onEndPluginGraphResolution()
    {
    }
    public function onBeginDirectoryTraversal(int $total, int $workers)
    {
    }
    public function onBeginAstTraversal(string $file)
    {
    }
    public function onEndAstTraversal(string $file, $iterationsOrError)
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
    public function onEndDirectoryTraversal()
    {
    }
    public function onBeginClassGraphMerge(int $count)
    {
    }
    public function onClassGraphMerged()
    {
    }
    public function onEndClassGraphMerge()
    {
    }
    public function onEnd()
    {
    }
}
