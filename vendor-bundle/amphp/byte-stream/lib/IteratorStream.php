<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Deferred;
use Phabel\Amp\Failure;
use Phabel\Amp\Iterator;
use Phabel\Amp\Promise;
final class IteratorStream implements InputStream
{
    /** @var Iterator<string> */
    private $iterator;
    /** @var \Throwable|null */
    private $exception;
    /** @var bool */
    private $pending = \false;
    /**
     * @psam-param Iterator<string> $iterator
     */
    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }
    /** @inheritdoc */
    public function read()
    {
        if ($this->exception) {
            $phabelReturn = new Failure($this->exception);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->pending) {
            throw new PendingReadError();
        }
        $this->pending = \true;
        /** @var Deferred<string|null> $deferred */
        $deferred = new Deferred();
        $this->iterator->advance()->onResolve(function ($error, $hasNextElement) use($deferred) {
            $this->pending = \false;
            if ($error) {
                $this->exception = $error;
                $deferred->fail($error);
            } elseif ($hasNextElement) {
                $chunk = $this->iterator->getCurrent();
                if (!\is_string($chunk)) {
                    $this->exception = new StreamException(\sprintf("Unexpected iterator value of type '%s', expected string", \is_object($chunk) ? \get_class($chunk) : \gettype($chunk)));
                    $deferred->fail($this->exception);
                    return;
                }
                $deferred->resolve($chunk);
            } else {
                $deferred->resolve();
            }
        });
        $phabelReturn = $deferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
