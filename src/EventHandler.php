<?php

namespace Phabel;

abstract class EventHandler implements \Phabel\EventHandlerInterface
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
    public function onBeginDirectoryTraversal($total, $workers)
    {
        if (!\is_int($total)) {
            if (!(\is_bool($total) || \is_numeric($total))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($total) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($total) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $total = (int) $total;
        }
        if (!\is_int($workers)) {
            if (!(\is_bool($workers) || \is_numeric($workers))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($workers) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($workers) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $workers = (int) $workers;
        }
    }
    public function onBeginAstTraversal($file)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
    }
    public function onEndAstTraversal($file, $iterationsOrError)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        if (!($iterationsOrError instanceof \Exception || $iterationsOrError instanceof \Error)) {
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
    public function onBeginClassGraphMerge($count)
    {
        if (!\is_int($count)) {
            if (!(\is_bool($count) || \is_numeric($count))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($count) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($count) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $count = (int) $count;
        }
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
