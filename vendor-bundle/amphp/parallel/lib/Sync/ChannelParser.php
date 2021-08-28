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
        $this->serializer = isset($serializer) ? $serializer : new NativeSerializer();
        parent::__construct(self::parser($callback, $this->serializer));
    }
    /**
     * @param mixed $data Data to encode to send over a channel.
     *
     * @return string Encoded data that can be parsed by this class.
     *
     * @throws SerializationException
     */
    public function encode($data)
    {
        $data = $this->serializer->serialize($data);
        $phabelReturn = \pack("CL", 0, \strlen($data)) . $data;
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
     * @param callable $push
     * @param Serializer $serializer
     *
     * @return \Generator
     *
     * @throws ChannelException
     * @throws SerializationException
     */
    private static function parser(callable $push, Serializer $serializer)
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
