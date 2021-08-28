<?php

namespace Phabel\Amp\Process;

use Phabel\Amp\ByteStream\InputStream;
use Phabel\Amp\ByteStream\PendingReadError;
use Phabel\Amp\ByteStream\ResourceInputStream;
use Phabel\Amp\ByteStream\StreamException;
use Phabel\Amp\Deferred;
use Phabel\Amp\Failure;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
final class ProcessInputStream implements InputStream
{
    /** @var Deferred */
    private $initialRead;
    /** @var bool */
    private $shouldClose = \false;
    /** @var bool */
    private $referenced = \true;
    /** @var ResourceInputStream */
    private $resourceStream;
    /** @var StreamException|null */
    private $error;
    public function __construct(Promise $resourceStreamPromise)
    {
        $resourceStreamPromise->onResolve(function ($error, $resourceStream) {
            if ($error) {
                $this->error = new StreamException("Failed to launch process", 0, $error);
                if ($this->initialRead) {
                    $initialRead = $this->initialRead;
                    $this->initialRead = null;
                    $initialRead->fail($this->error);
                }
                return;
            }
            $this->resourceStream = $resourceStream;
            if (!$this->referenced) {
                $this->resourceStream->unreference();
            }
            if ($this->shouldClose) {
                $this->resourceStream->close();
            }
            if ($this->initialRead) {
                $initialRead = $this->initialRead;
                $this->initialRead = null;
                $initialRead->resolve($this->shouldClose ? null : $this->resourceStream->read());
            }
        });
    }
    /**
     * Reads data from the stream.
     *
     * @return Promise Resolves with a string when new data is available or `null` if the stream has closed.
     *
     * @throws PendingReadError Thrown if another read operation is still pending.
     */
    public function read()
    {
        if ($this->initialRead) {
            throw new PendingReadError();
        }
        if ($this->error) {
            $phabelReturn = new Failure($this->error);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->resourceStream) {
            $phabelReturn = $this->resourceStream->read();
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->shouldClose) {
            $phabelReturn = new Success();
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
            // Resolve reads on closed streams with null.
        }
        $this->initialRead = new Deferred();
        $phabelReturn = $this->initialRead->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function reference()
    {
        $this->referenced = \true;
        if ($this->resourceStream) {
            $this->resourceStream->reference();
        }
    }
    public function unreference()
    {
        $this->referenced = \false;
        if ($this->resourceStream) {
            $this->resourceStream->unreference();
        }
    }
    public function close()
    {
        $this->shouldClose = \true;
        if ($this->initialRead) {
            $initialRead = $this->initialRead;
            $this->initialRead = null;
            $initialRead->resolve();
        }
        if ($this->resourceStream) {
            $this->resourceStream->close();
        }
    }
}
