<?php

namespace Phabel\Amp;

/**
 * Creates a promise that resolves itself with a given value after a number of milliseconds.
 *
 * @template-covariant TReturn
 * @template-implements Promise<TReturn>
 */
final class Delayed implements Promise
{
    use Internal\Placeholder;
    /** @var string|null Event loop watcher identifier. */
    private $watcher;
    /**
     * @param int     $time Milliseconds before succeeding the promise.
     * @param TReturn $value Succeed the promise with this value.
     */
    public function __construct($time, $value = null)
    {
        if (!\is_int($time)) {
            if (!(\is_bool($time) || \is_numeric($time))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($time) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($time) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $time = (int) $time;
            }
        }
        $this->watcher = Loop::delay($time, function () use($value) {
            $this->watcher = null;
            $this->resolve($value);
        });
    }
    /**
     * References the internal watcher in the event loop, keeping the loop running while this promise is pending.
     *
     * @return self
     */
    public function reference()
    {
        if ($this->watcher !== null) {
            Loop::reference($this->watcher);
        }
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Unreferences the internal watcher in the event loop, allowing the loop to stop while this promise is pending if
     * no other events are pending in the loop.
     *
     * @return self
     */
    public function unreference()
    {
        if ($this->watcher !== null) {
            Loop::unreference($this->watcher);
        }
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
