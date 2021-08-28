<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Promise;
use Phabel\Amp\Success;
/**
 * Input stream with a single already known data chunk.
 */
final class InMemoryStream implements InputStream
{
    private $contents;
    /**
     * @param string|null $contents Data chunk or `null` for no data chunk.
     */
    public function __construct($contents = null)
    {
        if (!\is_null($contents)) {
            if (!\is_string($contents)) {
                if (!(\is_string($contents) || \is_object($contents) && \method_exists($contents, '__toString') || (\is_bool($contents) || \is_numeric($contents)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($contents) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($contents) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $contents = (string) $contents;
                }
            }
        }
        $this->contents = $contents;
    }
    /**
     * Reads data from the stream.
     *
     * @return Promise<string|null> Resolves with the full contents or `null` if the stream has closed / already been consumed.
     */
    public function read()
    {
        if ($this->contents === null) {
            $phabelReturn = new Success();
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $promise = new Success($this->contents);
        $this->contents = null;
        $phabelReturn = $promise;
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
