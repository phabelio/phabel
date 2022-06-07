<?php

namespace PhabelVendor\Amp\ByteStream\Base64;

use PhabelVendor\Amp\ByteStream\InputStream;
use PhabelVendor\Amp\Promise;
use function PhabelVendor\Amp\call;
final class Base64EncodingInputStream implements InputStream
{
    /** @var InputStream */
    private $source;
    /** @var string|null */
    private $buffer = '';
    /**
     *
     */
    public function __construct(InputStream $source)
    {
        $this->source = $source;
    }
    /**
     *
     */
    public function read() : Promise
    {
        return call(function () {
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
            $chunk = \base64_encode(\Phabel\Target\Php80\Polyfill::substr($this->buffer, 0, $length - $length % 3));
            $this->buffer = \Phabel\Target\Php80\Polyfill::substr($this->buffer, $length - $length % 3);
            return $chunk;
        });
    }
}
