<?php

namespace Phabel\Amp\ByteStream\Base64;

use Phabel\Amp\ByteStream\OutputStream;
use Phabel\Amp\ByteStream\StreamException;
use Phabel\Amp\Failure;
use Phabel\Amp\Promise;
final class Base64DecodingOutputStream implements OutputStream
{
    /** @var OutputStream */
    private $destination;
    /** @var string */
    private $buffer = '';
    /** @var int */
    private $offset = 0;
    public function __construct(OutputStream $destination)
    {
        $this->destination = $destination;
    }
    public function write($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        $this->buffer .= $data;
        $length = \strlen($this->buffer);
        $chunk = \base64_decode(\substr($this->buffer, 0, $length - $length % 4), \true);
        if ($chunk === \false) {
            $phabelReturn = new Failure(new StreamException('Invalid base64 near offset ' . $this->offset));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $this->offset += $length - $length % 4;
        $this->buffer = \substr($this->buffer, $length - $length % 4);
        $phabelReturn = $this->destination->write($chunk);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function end($finalData = "")
    {
        if (!\is_string($finalData)) {
            if (!(\is_string($finalData) || \is_object($finalData) && \method_exists($finalData, '__toString') || (\is_bool($finalData) || \is_numeric($finalData)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($finalData) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($finalData) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $finalData = (string) $finalData;
            }
        }
        $this->offset += \strlen($this->buffer);
        $chunk = \base64_decode($this->buffer . $finalData, \true);
        if ($chunk === \false) {
            $phabelReturn = new Failure(new StreamException('Invalid base64 near offset ' . $this->offset));
            if (!$phabelReturn instanceof Promise) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $this->buffer = '';
        $phabelReturn = $this->destination->end($chunk);
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
