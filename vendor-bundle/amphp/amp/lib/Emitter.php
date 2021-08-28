<?php

namespace Phabel\Amp;

/**
 * Emitter is a container for an iterator that can emit values using the emit() method and completed using the
 * complete() and fail() methods of this object. The contained iterator may be accessed using the iterate()
 * method. This object should not be part of a public API, but used internally to create and emit values to an
 * iterator.
 *
 * @template TValue
 */
final class Emitter
{
    /** @var Iterator<TValue> Has public emit, complete, and fail methods. */
    private $emitter;
    /** @var Iterator<TValue> Hides producer methods. */
    private $iterator;
    public function __construct()
    {
        $this->emitter = new PhabelAnonymousClassa53edb6f53fd8f3c2c413cbfc1be5e5edab46efe1086ce21f4f026e485307bc71();
        $this->iterator = new Internal\PrivateIterator($this->emitter);
    }
    /**
     * @return Iterator
     * @psalm-return Iterator<TValue>
     */
    public function iterate()
    {
        $phabelReturn = $this->iterator;
        if (!$phabelReturn instanceof Iterator) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Iterator, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Emits a value to the iterator.
     *
     * @param mixed $value
     *
     * @psalm-param TValue $value
     *
     * @return Promise
     * @psalm-return Promise<null>
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public function emit($value)
    {
        $phabelReturn = $this->emitter->emit($value);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $phabelReturn;
    }
    /**
     * Completes the iterator.
     *
     * @return void
     */
    public function complete()
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->emitter->complete();
    }
    /**
     * Fails the iterator with the given reason.
     *
     * @param \Throwable $reason
     *
     * @return void
     */
    public function fail(\Throwable $reason)
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->emitter->fail($reason);
    }
}
if (!\class_exists(PhabelAnonymousClassa53edb6f53fd8f3c2c413cbfc1be5e5edab46efe1086ce21f4f026e485307bc71::class)) {
    class PhabelAnonymousClassa53edb6f53fd8f3c2c413cbfc1be5e5edab46efe1086ce21f4f026e485307bc71 implements Iterator, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        use Internal\Producer {
            emit as public;
            complete as public;
            fail as public;
        }
        public static function getPhabelOriginalName()
        {
            return Iterator::class . '@anonymous';
        }
    }
}
