<?php

namespace Phabel\Amp;

/**
 * Deferred is a container for a promise that is resolved using the resolve() and fail() methods of this object.
 * The contained promise may be accessed using the promise() method. This object should not be part of a public
 * API, but used internally to create and resolve a promise.
 *
 * @template TValue
 */
final class Deferred
{
    /** @var Promise<TValue> Has public resolve and fail methods. */
    private $resolver;
    /** @var Promise<TValue> Hides placeholder methods */
    private $promise;
    public function __construct()
    {
        $this->resolver = new PhabelAnonymousClass78674b341fc8ce1a3a6d684af60fb5c39331bf7c50114a496f0a39e563cb3d833();
        $this->promise = new Internal\PrivatePromise($this->resolver);
    }
    /**
     * @return Promise<TValue>
     */
    public function promise()
    {
        $phabelReturn = $this->promise;
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Fulfill the promise with the given value.
     *
     * @param mixed $value
     *
     * @psalm-param TValue|Promise<TValue> $value
     *
     * @return void
     */
    public function resolve($value = null)
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->resolver->resolve($value);
    }
    /**
     * Fails the promise the the given reason.
     *
     * @param \Throwable $reason
     *
     * @return void
     */
    public function fail(\Throwable $reason)
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->resolver->fail($reason);
    }
    /**
     * @return bool True if the promise has been resolved.
     */
    public function isResolved()
    {
        $phabelReturn = $this->resolver->isResolved();
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\class_exists(PhabelAnonymousClass78674b341fc8ce1a3a6d684af60fb5c39331bf7c50114a496f0a39e563cb3d833::class)) {
    class PhabelAnonymousClass78674b341fc8ce1a3a6d684af60fb5c39331bf7c50114a496f0a39e563cb3d833 implements Promise, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        use Internal\Placeholder {
            resolve as public;
            fail as public;
            isResolved as public;
        }
        public static function getPhabelOriginalName()
        {
            return Promise::class . '@anonymous';
        }
    }
}
