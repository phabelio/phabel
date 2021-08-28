<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Promise;
/**
 * Allows compression of output streams using Zlib.
 */
final class ZlibOutputStream implements OutputStream
{
    /** @var OutputStream|null */
    private $destination;
    /** @var int */
    private $encoding;
    /** @var array */
    private $options;
    /** @var resource|null */
    private $resource;
    /**
     * @param OutputStream $destination Output stream to write the compressed data to.
     * @param int          $encoding Compression encoding to use, see `deflate_init()`.
     * @param array        $options Compression options to use, see `deflate_init()`.
     *
     * @throws StreamException If an invalid encoding or invalid options have been passed.
     *
     * @see http://php.net/manual/en/function.deflate-init.php
     */
    public function __construct(OutputStream $destination, $encoding, array $options = [])
    {
        if (!\is_int($encoding)) {
            if (!(\is_bool($encoding) || \is_numeric($encoding))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $encoding = (int) $encoding;
            }
        }
        $this->destination = $destination;
        $this->encoding = $encoding;
        $this->options = $options;
        $this->resource = @\deflate_init($encoding, $options);
        if ($this->resource === \false) {
            throw new StreamException("Failed initializing deflate context");
        }
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
        if ($this->resource === null) {
            throw new ClosedException("The stream has already been closed");
        }
        \assert($this->destination !== null);
        $compressed = \deflate_add($this->resource, $data, \ZLIB_SYNC_FLUSH);
        if ($compressed === \false) {
            throw new StreamException("Failed adding data to deflate context");
        }
        $promise = $this->destination->write($compressed);
        $promise->onResolve(function ($error) {
            if ($error) {
                $this->close();
            }
        });
        $phabelReturn = $promise;
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
        if ($this->resource === null) {
            throw new ClosedException("The stream has already been closed");
        }
        \assert($this->destination !== null);
        $compressed = \deflate_add($this->resource, $finalData, \ZLIB_FINISH);
        if ($compressed === \false) {
            throw new StreamException("Failed adding data to deflate context");
        }
        $promise = $this->destination->end($compressed);
        $promise->onResolve(function () {
            $this->close();
        });
        $phabelReturn = $promise;
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @internal
     * @return void
     */
    private function close()
    {
        $this->resource = null;
        $this->destination = null;
    }
    /**
     * Gets the used compression encoding.
     *
     * @return int Encoding specified on construction time.
     */
    public function getEncoding()
    {
        $phabelReturn = $this->encoding;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * Gets the used compression options.
     *
     * @return array Options array passed on construction time.
     */
    public function getOptions()
    {
        $phabelReturn = $this->options;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
