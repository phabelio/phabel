<?php

namespace Phabel\Amp\Process;

use Phabel\Amp\ByteStream\ClosedException;
use Phabel\Amp\ByteStream\OutputStream;
use Phabel\Amp\ByteStream\ResourceOutputStream;
use Phabel\Amp\ByteStream\StreamException;
use Phabel\Amp\Deferred;
use Phabel\Amp\Failure;
use Phabel\Amp\Promise;
final class ProcessOutputStream implements OutputStream
{
    /** @var \SplQueue */
    private $queuedWrites;
    /** @var bool */
    private $shouldClose = \false;
    /** @var ResourceOutputStream */
    private $resourceStream;
    /** @var StreamException|null */
    private $error;
    public function __construct(Promise $resourceStreamPromise)
    {
        $this->queuedWrites = new \SplQueue();
        $resourceStreamPromise->onResolve(function ($error, $resourceStream) {
            if ($error) {
                $this->error = new StreamException("Failed to launch process", 0, $error);
                while (!$this->queuedWrites->isEmpty()) {
                    list(, $deferred) = $this->queuedWrites->shift();
                    $deferred->fail($this->error);
                }
                return;
            }
            while (!$this->queuedWrites->isEmpty()) {
                /**
                 * @var string $data
                 * @var \Amp\Deferred $deferred
                 */
                list($data, $deferred) = $this->queuedWrites->shift();
                $deferred->resolve($resourceStream->write($data));
            }
            $this->resourceStream = $resourceStream;
            if ($this->shouldClose) {
                $this->resourceStream->close();
            }
        });
    }
    /** @inheritdoc */
    public function write($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        if ($this->resourceStream) {
            $phabelReturn = $this->resourceStream->write($data);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->error) {
            $phabelReturn = new Failure($this->error);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->shouldClose) {
            throw new ClosedException("Stream has already been closed.");
        }
        $deferred = new Deferred();
        $this->queuedWrites->push([$data, $deferred]);
        $phabelReturn = $deferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function end($finalData = "")
    {
        if (!\is_string($finalData)) {
            if (!(\is_string($finalData) || \is_object($finalData) && \method_exists($finalData, '__toString') || (\is_bool($finalData) || \is_numeric($finalData)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($finalData) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($finalData) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $finalData = (string) $finalData;
            }
        }
        if ($this->resourceStream) {
            $phabelReturn = $this->resourceStream->end($finalData);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->error) {
            $phabelReturn = new Failure($this->error);
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ($this->shouldClose) {
            throw new ClosedException("Stream has already been closed.");
        }
        $deferred = new Deferred();
        $this->queuedWrites->push([$finalData, $deferred]);
        $this->shouldClose = \true;
        $phabelReturn = $deferred->promise();
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function close()
    {
        $this->shouldClose = \true;
        if ($this->resourceStream) {
            $this->resourceStream->close();
        } elseif (!$this->queuedWrites->isEmpty()) {
            $error = new ClosedException("Stream closed.");
            do {
                list(, $deferred) = $this->queuedWrites->shift();
                $deferred->fail($error);
            } while (!$this->queuedWrites->isEmpty());
        }
    }
}
