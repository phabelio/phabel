<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class LocalKeyedSemaphore implements KeyedSemaphore
{
    /** @var LocalSemaphore[] */
    private $semaphore = [];
    /** @var int[] */
    private $locks = [];
    /** @var int */
    private $maxLocks;
    public function __construct($maxLocks)
    {
        if (!\is_int($maxLocks)) {
            if (!(\is_bool($maxLocks) || \is_numeric($maxLocks))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($maxLocks) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($maxLocks) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $maxLocks = (int) $maxLocks;
            }
        }
        $this->maxLocks = $maxLocks;
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
        if (!isset($this->semaphore[$key])) {
            $this->semaphore[$key] = new LocalSemaphore($this->maxLocks);
            $this->locks[$key] = 0;
        }
        $phabelReturn = call(function () use($key) {
            $this->locks[$key]++;
            /** @var Lock $lock */
            $lock = (yield $this->semaphore[$key]->acquire());
            return new Lock(0, function () use($lock, $key) {
                if (--$this->locks[$key] === 0) {
                    unset($this->semaphore[$key], $this->locks[$key]);
                }
                $lock->release();
            });
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
