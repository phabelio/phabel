<?php

namespace Phabel\Amp\ByteStream;

use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class LineReader
{
    /** @var string */
    private $delimiter;
    /** @var bool */
    private $lineMode;
    /** @var string */
    private $buffer = "";
    /** @var InputStream */
    private $source;
    public function __construct(InputStream $inputStream, $delimiter = null)
    {
        if (!\is_null($delimiter)) {
            if (!\is_string($delimiter)) {
                if (!(\is_string($delimiter) || \is_object($delimiter) && \method_exists($delimiter, '__toString') || (\is_bool($delimiter) || \is_numeric($delimiter)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($delimiter) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($delimiter) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $delimiter = (string) $delimiter;
                }
            }
        }
        $this->source = $inputStream;
        $this->delimiter = $delimiter === null ? "\n" : $delimiter;
        $this->lineMode = $delimiter === null;
    }
    /**
     * @return Promise<string|null>
     */
    public function readLine()
    {
        $phabelReturn = call(function () {
            if (\false !== \strpos($this->buffer, $this->delimiter)) {
                list($line, $this->buffer) = \explode($this->delimiter, $this->buffer, 2);
                return $this->lineMode ? \rtrim($line, "\r") : $line;
            }
            while (null !== ($chunk = (yield $this->source->read()))) {
                $this->buffer .= $chunk;
                if (\false !== \strpos($this->buffer, $this->delimiter)) {
                    list($line, $this->buffer) = \explode($this->delimiter, $this->buffer, 2);
                    return $this->lineMode ? \rtrim($line, "\r") : $line;
                }
            }
            if ($this->buffer === "") {
                return null;
            }
            $line = $this->buffer;
            $this->buffer = "";
            return $this->lineMode ? \rtrim($line, "\r") : $line;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getBuffer()
    {
        $phabelReturn = $this->buffer;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * @return void
     */
    public function clearBuffer()
    {
        $this->buffer = "";
    }
}
