<?php

namespace Phabel\Amp\Sync\Internal;

use Phabel\Amp\Delayed;
use Phabel\Amp\Promise;
use Phabel\Amp\Sync\Lock;
use function Phabel\Amp\call;
/** @internal */
final class MutexStorage extends \Threaded
{
    const LATENCY_TIMEOUT = 10;
    /** @var bool */
    private $locked = \false;
    public function acquire()
    {
        $phabelReturn = call(function () {
            $tsl = function () {
                if ($this->locked) {
                    $phabelReturn = \true;
                    if (!\is_bool($phabelReturn)) {
                        if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $phabelReturn = (bool) $phabelReturn;
                        }
                    }
                    return $phabelReturn;
                }
                $this->locked = \true;
                $phabelReturn = \false;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            };
            while ($this->locked || $this->synchronized($tsl)) {
                (yield new Delayed(self::LATENCY_TIMEOUT));
            }
            return new Lock(0, function () {
                $this->locked = \false;
            });
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
