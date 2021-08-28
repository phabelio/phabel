<?php

namespace Phabel\Amp\Serialization;

final class CompressingSerializer implements Serializer
{
    const FLAG_COMPRESSED = 1;
    const COMPRESSION_THRESHOLD = 256;
    /** @var Serializer */
    private $serializer;
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
    public function serialize($data)
    {
        $serializedData = $this->serializer->serialize($data);
        $flags = 0;
        if (\strlen($serializedData) > self::COMPRESSION_THRESHOLD) {
            $serializedData = @\gzdeflate($serializedData, 1);
            if ($serializedData === \false) {
                $error = \error_get_last();
                throw new SerializationException('Could not compress data: ' . (isset($error['message']) ? $error['message'] : 'unknown error'));
            }
            $flags |= self::FLAG_COMPRESSED;
        }
        $phabelReturn = \chr($flags & 0xff) . $serializedData;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function unserialize($data)
    {
        if (!\is_string($data)) {
            if (!(\is_string($data) || \is_object($data) && \method_exists($data, '__toString') || (\is_bool($data) || \is_numeric($data)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($data) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($data) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $data = (string) $data;
            }
        }
        $firstByte = \ord($data[0]);
        $data = \substr($data, 1);
        if ($firstByte & self::FLAG_COMPRESSED) {
            $data = @\gzinflate($data);
            if ($data === \false) {
                $error = \error_get_last();
                throw new SerializationException('Could not decompress data: ' . (isset($error['message']) ? $error['message'] : 'unknown error'));
            }
        }
        return $this->serializer->unserialize($data);
    }
}
