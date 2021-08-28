<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Deferred;
use Phabel\Amp\Failure;
use Phabel\Amp\Loop;
use Phabel\Amp\Promise;
use Phabel\Amp\Success;
/**
 * Output stream abstraction for PHP's stream resources.
 */
final class ResourceOutputStream implements OutputStream
{
    const MAX_CONSECUTIVE_EMPTY_WRITES = 3;
    const LARGE_CHUNK_SIZE = 128 * 1024;
    /** @var resource|null */
    private $resource;
    /** @var string */
    private $watcher;
    /** @var \SplQueue<array> */
    private $writes;
    /** @var bool */
    private $writable = \true;
    /** @var int|null */
    private $chunkSize;
    /**
     * @param resource $stream Stream resource.
     * @param int|null $chunkSize Chunk size per `fwrite()` operation.
     */
    public function __construct($stream, $chunkSize = null)
    {
        if (!\is_null($chunkSize)) {
            if (!\is_int($chunkSize)) {
                if (!(\is_bool($chunkSize) || \is_numeric($chunkSize))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($chunkSize) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($chunkSize) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $chunkSize = (int) $chunkSize;
                }
            }
        }
        if (!\is_resource($stream) || \get_resource_type($stream) !== 'stream') {
            throw new \Error("Expected a valid stream");
        }
        $meta = \stream_get_meta_data($stream);
        if (\strpos($meta["mode"], "r") !== \false && \strpos($meta["mode"], "+") === \false) {
            throw new \Error("Expected a writable stream");
        }
        \stream_set_blocking($stream, \false);
        \stream_set_write_buffer($stream, 0);
        $this->resource = $stream;
        $this->chunkSize =& $chunkSize;
        $writes = $this->writes = new \SplQueue();
        $writable =& $this->writable;
        $resource =& $this->resource;
        $this->watcher = Loop::onWritable($stream, static function ($watcher, $stream) use($writes, &$chunkSize, &$writable, &$resource) {
            static $emptyWrites = 0;
            try {
                while (!$writes->isEmpty()) {
                    /** @var Deferred $deferred */
                    list($data, $previous, $deferred) = $writes->shift();
                    $length = \strlen($data);
                    if ($length === 0) {
                        $deferred->resolve(0);
                        continue;
                    }
                    if (!\is_resource($stream) || ($metaData = @\stream_get_meta_data($stream)) && $metaData['eof']) {
                        throw new ClosedException("The stream was closed by the peer");
                    }
                    // Error reporting suppressed since fwrite() emits E_WARNING if the pipe is broken or the buffer is full.
                    // Use conditional, because PHP doesn't like getting null passed
                    if ($chunkSize) {
                        $written = @\fwrite($stream, $data, $chunkSize);
                    } else {
                        $written = @\fwrite($stream, $data);
                    }
                    \assert(
                        $written !== \false || \PHP_VERSION_ID >= 70400,
                        // PHP 7.4+ returns false on EPIPE.
                        "Trying to write on a previously fclose()'d resource. Do NOT manually fclose() resources the still referenced in the loop."
                    );
                    // PHP 7.4.0 and 7.4.1 may return false on EAGAIN.
                    if ($written === \false && \PHP_VERSION_ID >= 70402) {
                        $message = "Failed to write to stream";
                        if ($error = \error_get_last()) {
                            $message .= \sprintf("; %s", $error["message"]);
                        }
                        throw new StreamException($message);
                    }
                    // Broken pipes between processes on macOS/FreeBSD do not detect EOF properly.
                    if ($written === 0 || $written === \false) {
                        if ($emptyWrites++ > self::MAX_CONSECUTIVE_EMPTY_WRITES) {
                            $message = "Failed to write to stream after multiple attempts";
                            if ($error = \error_get_last()) {
                                $message .= \sprintf("; %s", $error["message"]);
                            }
                            throw new StreamException($message);
                        }
                        $writes->unshift([$data, $previous, $deferred]);
                        return;
                    }
                    $emptyWrites = 0;
                    if ($length > $written) {
                        $data = \substr($data, $written);
                        $writes->unshift([$data, $written + $previous, $deferred]);
                        return;
                    }
                    $deferred->resolve($written + $previous);
                }
            } catch (\Exception $exception) {
                $resource = null;
                $writable = \false;
                /** @psalm-suppress PossiblyUndefinedVariable */
                $deferred->fail($exception);
                while (!$writes->isEmpty()) {
                    list(, , $deferred) = $writes->shift();
                    $deferred->fail($exception);
                }
                Loop::cancel($watcher);
            } catch (\Error $exception) {
                $resource = null;
                $writable = \false;
                /** @psalm-suppress PossiblyUndefinedVariable */
                $deferred->fail($exception);
                while (!$writes->isEmpty()) {
                    list(, , $deferred) = $writes->shift();
                    $deferred->fail($exception);
                }
                Loop::cancel($watcher);
            } finally {
                if ($writes->isEmpty()) {
                    Loop::disable($watcher);
                }
            }
        });
        Loop::disable($this->watcher);
    }
    /**
     * Writes data to the stream.
     *
     * @param string $data Bytes to write.
     *
     * @return Promise Succeeds once the data has been successfully written to the stream.
     *
     * @throws ClosedException If the stream has already been closed.
     */
    public function write($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        $phabelReturn = $this->send($data, \false);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Closes the stream after all pending writes have been completed. Optionally writes a final data chunk before.
     *
     * @param string $finalData Bytes to write.
     *
     * @return Promise Succeeds once the data has been successfully written to the stream.
     *
     * @throws ClosedException If the stream has already been closed.
     */
    public function end($finalData = "")
    {
        if (!\is_string($finalData)) {
            if (!(\is_string($finalData) || \is_object($finalData) && \method_exists($finalData, '__toString') || (\is_bool($finalData) || \is_numeric($finalData)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($finalData) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($finalData) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $finalData = (string) $finalData;
            }
        }
        $phabelReturn = $this->send($finalData, \true);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function send($data, $end = \false)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        if (!\is_bool($end)) {
            if (!(\is_bool($end) || \is_numeric($end) || \is_string($end))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($end) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($end) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $end = (bool) $end;
            }
        }
        if (!$this->writable) {
            $phabelReturn = new Failure(new ClosedException("The stream is not writable"));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $length = \strlen($data);
        $written = 0;
        if ($end) {
            $this->writable = \false;
        }
        if ($this->writes->isEmpty()) {
            if ($length === 0) {
                if ($end) {
                    $this->close();
                }
                $phabelReturn = new Success(0);
                if (!$phabelReturn instanceof Promise) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            if (!\is_resource($this->resource) || ($metaData = @\stream_get_meta_data($this->resource)) && $metaData['eof']) {
                $phabelReturn = new Failure(new ClosedException("The stream was closed by the peer"));
                if (!$phabelReturn instanceof Promise) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            // Error reporting suppressed since fwrite() emits E_WARNING if the pipe is broken or the buffer is full.
            // Use conditional, because PHP doesn't like getting null passed.
            if ($this->chunkSize) {
                $written = @\fwrite($this->resource, $data, $this->chunkSize);
            } else {
                $written = @\fwrite($this->resource, $data);
            }
            \assert(
                $written !== \false || \PHP_VERSION_ID >= 70400,
                // PHP 7.4+ returns false on EPIPE.
                "Trying to write on a previously fclose()'d resource. Do NOT manually fclose() resources the still referenced in the loop."
            );
            // PHP 7.4.0 and 7.4.1 may return false on EAGAIN.
            if ($written === \false && \PHP_VERSION_ID >= 70402) {
                $message = "Failed to write to stream";
                if ($error = \error_get_last()) {
                    $message .= \sprintf("; %s", $error["message"]);
                }
                $phabelReturn = new Failure(new StreamException($message));
                if (!$phabelReturn instanceof Promise) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            $written = (int) $written;
            // Cast potential false to 0.
            if ($length === $written) {
                if ($end) {
                    $this->close();
                }
                $phabelReturn = new Success($written);
                if (!$phabelReturn instanceof Promise) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            $data = \substr($data, $written);
        }
        $deferred = new Deferred();
        if ($length - $written > self::LARGE_CHUNK_SIZE) {
            $chunks = \str_split($data, self::LARGE_CHUNK_SIZE);
            $data = \array_pop($chunks);
            foreach ($chunks as $chunk) {
                $this->writes->push([$chunk, $written, new Deferred()]);
                $written += self::LARGE_CHUNK_SIZE;
            }
        }
        $this->writes->push([$data, $written, $deferred]);
        Loop::enable($this->watcher);
        $promise = $deferred->promise();
        if ($end) {
            $promise->onResolve([$this, "close"]);
        }
        $phabelReturn = $promise;
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Closes the stream forcefully. Multiple `close()` calls are ignored.
     *
     * @return void
     */
    public function close()
    {
        if (\is_resource($this->resource)) {
            // Error suppression, as resource might already be closed
            $meta = @\stream_get_meta_data($this->resource);
            if ($meta && \strpos($meta["mode"], "+") !== \false) {
                @\stream_socket_shutdown($this->resource, \STREAM_SHUT_WR);
            } else {
                /** @psalm-suppress InvalidPropertyAssignmentValue psalm reports this as closed-resource */
                @\fclose($this->resource);
            }
        }
        $this->free();
    }
    /**
     * Nulls reference to resource, marks stream unwritable, and fails any pending write.
     *
     * @return void
     */
    private function free()
    {
        $this->resource = null;
        $this->writable = \false;
        if (!$this->writes->isEmpty()) {
            $exception = new ClosedException("The socket was closed before writing completed");
            do {
                /** @var Deferred $deferred */
                list(, , $deferred) = $this->writes->shift();
                $deferred->fail($exception);
            } while (!$this->writes->isEmpty());
        }
        Loop::cancel($this->watcher);
    }
    /**
     * @return resource|null Stream resource or null if end() has been called or the stream closed.
     */
    public function getResource()
    {
        return $this->resource;
    }
    /**
     * @return void
     */
    public function setChunkSize($chunkSize)
    {
        if (!\is_int($chunkSize)) {
            if (!(\is_bool($chunkSize) || \is_numeric($chunkSize))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($chunkSize) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($chunkSize) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $chunkSize = (int) $chunkSize;
            }
        }
        $this->chunkSize = $chunkSize;
    }
    public function __destruct()
    {
        if ($this->resource !== null) {
            $this->free();
        }
    }
}
