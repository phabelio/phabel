<?php

namespace Phabel\Amp\ByteStream\Base64;

use Phabel\Amp\ByteStream\InputStream;
use Phabel\Amp\Promise;
use function Phabel\Amp\call;
final class Base64EncodingInputStream implements InputStream
{
    /** @var InputStream */
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
            $chunk = (yield $this->source->read());
            if ($chunk === null) {
                if ($this->buffer === null) {
                    return null;
                }
                $chunk = \base64_encode($this->buffer);
                $this->buffer = null;
                return $chunk;
            }
            $this->buffer .= $chunk;
            $length = \strlen($this->buffer);
            $chunk = \base64_encode(\substr($this->buffer, 0, $length - $length % 3));
            $this->buffer = \substr($this->buffer, $length - $length % 3);
            return $chunk;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
