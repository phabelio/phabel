<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
final class StaticKeyMutex implements Mutex
{
    /** @var KeyedMutex */
    private $mutex;
    /** @var string */
    private $key;
    public function __construct(KeyedMutex $mutex, $key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        $this->mutex = $mutex;
        $this->key = $key;
    }
    public function acquire()
    {
        $phabelReturn = $this->mutex->acquire($this->key);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
