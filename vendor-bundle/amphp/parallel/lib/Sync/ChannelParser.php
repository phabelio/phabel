<?php

namespace PhabelVendor\Amp\Parallel\Sync;

use PhabelVendor\Amp\Parser\Parser;
use PhabelVendor\Amp\Serialization\NativeSerializer;
use PhabelVendor\Amp\Serialization\Serializer;
use function PhabelVendor\Amp\Serialization\encodeUnprintableChars;
final class ChannelParser extends Parser
{
    const HEADER_LENGTH = 5;
    /** @var Serializer */
    private $serializer;
    /**
     * @param callable(mixed $data) Callback invoked when data is parsed.
     * @param Serializer|null $serializer
     */
    public function __construct(callable $callback, ?Serializer $serializer = null)
    {
        $this->serializer = $serializer ?? new NativeSerializer();
        parent::__construct(self::parser($callback, $this->serializer));
    }
    /**
     * @param mixed $data Data to encode to send over a channel.
     *
     * @return string Encoded data that can be parsed by this class.
     *
     * @throws SerializationException
     */
    public function encode($data) : string
    {
        $data = $this->serializer->serialize($data);
        return \pack("CL", 0, \strlen($data)) . $data;
    }
    /**
     * @param callable $push
     * @param Serializer $serializer
     *
     * @return \Generator
     *
     * @throws ChannelException
     * @throws SerializationException
     */
    private static function parser(callable $push, Serializer $serializer) : \Generator
    {
        while (\true) {
            $header = (yield self::HEADER_LENGTH);
            $data = \unpack("Cprefix/Llength", $header);
            if ($data["prefix"] !== 0) {
                $data = $header . yield;
                throw new ChannelException("Invalid packet received: " . encodeUnprintableChars($data));
            }
            $data = (yield $data["length"]);
            $push($serializer->unserialize($data));
        }
    }
}
