<?php

namespace Phabel\Amp\Internal;

use Phabel\Amp\Iterator;
use Phabel\Amp\Promise;
/**
 * Wraps an Iterator instance that has public methods to emit, complete, and fail into an object that only allows
 * access to the public API methods.
 *
 * @template-covariant TValue
 * @template-implements Iterator<TValue>
 */
final class PrivateIterator implements Iterator
{
    /** @var Iterator<TValue> */
    private $iterator;
    /**
     * @param Iterator $iterator
     *
     * @psalm-param Iterator<TValue> $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }
    /**
     * @return Promise<bool>
     */
    public function advance()
    {
        $phabelReturn = $this->iterator->advance();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @psalm-return TValue
     */
    public function getCurrent()
    {
        return $this->iterator->getCurrent();
    }
}
