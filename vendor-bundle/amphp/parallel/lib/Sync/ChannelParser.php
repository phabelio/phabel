<?php

namespace Phabel\Amp\Parallel\Sync;

use Phabel\Amp\Parser\Parser;
use Phabel\Amp\Serialization\NativeSerializer;
use Phabel\Amp\Serialization\Serializer;
use function Phabel\Amp\Serialization\encodeUnprintableChars;
final class ChannelParser extends Parser
{
    const HEADER_LENGTH = 5;
    /** @var Serializer */
    private $serializer;
    /**
     * @param callable(mixed $data) Callback invoked when data is parsed.
     * @param Serializer|null $serializer
     */
    public function __construct(callable $callback, $serializer = null)
    {
        if (!($serializer instanceof Serializer || \is_null($serializer))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($serializer) must be of type ?Serializer, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($serializer) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
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
            $data = \Phabel\Target\Php71\Polyfill::unpack("Cprefix/Llength", $header);
            if ($data["prefix"] !== 0) {
                $data = $header . yield;
                throw new ChannelException("Invalid packet received: " . encodeUnprintableChars($data));
            }
            $data = (yield $data["length"]);
            $push($serializer->unserialize($data));
        }
    }
}
