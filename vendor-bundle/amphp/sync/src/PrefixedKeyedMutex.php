<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
final class PrefixedKeyedMutex implements KeyedMutex
{
    /** @var KeyedMutex */
    private $mutex;
    /** @var string */
    private $prefix;
    public function __construct(KeyedMutex $mutex, $prefix)
    {
        if (!\is_string($prefix)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        $this->mutex = $mutex;
        $this->prefix = $prefix;
    }
    public function acquire($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        $phabelReturn = $this->mutex->acquire($this->prefix . $key);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
