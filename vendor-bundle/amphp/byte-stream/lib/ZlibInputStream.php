<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
/**
 * Allows decompression of input streams using Zlib.
 */
final class ZlibInputStream implements InputStream
{
    /** @var InputStream|null */
    private $source;
    /** @var int */
    private $encoding;
    /** @var array */
    private $options;
    /** @var resource|null */
    private $resource;
    /**
     * @param InputStream $source Input stream to read compressed data from.
     * @param int         $encoding Compression algorithm used, see `inflate_init()`.
     * @param array       $options Algorithm options, see `inflate_init()`.
     *
     * @throws StreamException
     * @throws \Error
     *
     * @see http://php.net/manual/en/function.inflate-init.php
     */
    public function __construct(InputStream $source, $encoding, array $options = [])
    {
        if (!\is_int($encoding)) {
            if (!(\is_bool($encoding) || \is_numeric($encoding))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $encoding = (int) $encoding;
            }
        }
        $this->source = $source;
        $this->encoding = $encoding;
        $this->options = $options;
        $this->resource = @\inflate_init($encoding, $options);
        if ($this->resource === \false) {
            throw new StreamException("Failed initializing deflate context");
        }
    }
    /** @inheritdoc */
    public function read()
    {
        $phabelReturn = call(function () {
            if ($this->resource === null) {
                return null;
            }
            \assert($this->source !== null);
            $data = (yield $this->source->read());
            // Needs a double guard, as stream might have been closed while reading
            /** @psalm-suppress ParadoxicalCondition */
            if ($this->resource === null) {
                return null;
            }
            if ($data === null) {
                $decompressed = @\inflate_add($this->resource, "", \ZLIB_FINISH);
                if ($decompressed === \false) {
                    throw new StreamException("Failed adding data to deflate context");
                }
                $this->close();
                return $decompressed;
            }
            $decompressed = @\inflate_add($this->resource, $data, \ZLIB_SYNC_FLUSH);
            if ($decompressed === \false) {
                throw new StreamException("Failed adding data to deflate context");
            }
            return $decompressed;
        });
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
        $this->source = null;
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
