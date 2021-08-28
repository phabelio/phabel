<?php

namespace Phabel\Amp\ByteStream\Base64;

use Phabel\Amp\ByteStream\InputStream;
use Phabel\Amp\ByteStream\StreamException;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class Base64DecodingInputStream implements InputStream
{
    /** @var InputStream|null */
    private $source;
    /** @var string|null */
    private $buffer = '';
    public function __construct(InputStream $source)
    {
        $this->source = $source;
    }
    public function read()
    {
        $phabelReturn = call(function () {
            if ($this->source === null) {
                throw new StreamException('Failed to read stream chunk due to invalid base64 data');
            }
            $chunk = (yield $this->source->read());
            if ($chunk === null) {
                if ($this->buffer === null) {
                    return null;
                }
                $chunk = \base64_decode($this->buffer, \true);
                if ($chunk === \false) {
                    $this->source = null;
                    $this->buffer = null;
                    throw new StreamException('Failed to read stream chunk due to invalid base64 data');
                }
                $this->buffer = null;
                return $chunk;
            }
            $this->buffer .= $chunk;
            $length = \strlen($this->buffer);
            $chunk = \base64_decode(\substr($this->buffer, 0, $length - $length % 4), \true);
            if ($chunk === \false) {
                $this->source = null;
                $this->buffer = null;
                throw new StreamException('Failed to read stream chunk due to invalid base64 data');
            }
            $this->buffer = \substr($this->buffer, $length - $length % 4);
            return $chunk;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
