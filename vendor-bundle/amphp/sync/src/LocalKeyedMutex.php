<?php

namespace Phabel\Amp\Sync;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class LocalKeyedMutex implements KeyedMutex
{
    /** @var LocalMutex[] */
    private $mutex = [];
    /** @var int[] */
    private $locks = [];
    public function acquire($key)
    {
        if (!\is_string($key)) {
            if (!(\is_string($key) || \is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $key = (string) $key;
            }
        }
        if (!isset($this->mutex[$key])) {
            $this->mutex[$key] = new LocalMutex();
            $this->locks[$key] = 0;
        }
        $phabelReturn = call(function () use($key) {
            $this->locks[$key]++;
            /** @var Lock $lock */
            $lock = (yield $this->mutex[$key]->acquire());
            return new Lock(0, function () use($lock, $key) {
                if (--$this->locks[$key] === 0) {
                    unset($this->mutex[$key], $this->locks[$key]);
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
