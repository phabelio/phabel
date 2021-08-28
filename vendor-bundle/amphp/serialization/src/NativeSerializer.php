<?php

namespace Phabel\Amp\Serialization;

final class NativeSerializer implements Serializer
{
    /** @var string[]|null */
    private $allowedClasses;
    /**
     * @param string[]|null $allowedClasses List of allowed class names to be unserialized. Null for any class.
     */
    public function __construct($allowedClasses = null)
    {
        if (!(\is_array($allowedClasses) || \is_null($allowedClasses))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($allowedClasses) must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowedClasses) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->allowedClasses = $allowedClasses;
    }
    public function serialize($data)
    {
        try {
            $phabelReturn = \serialize($data);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        } catch (\Exception $exception) {
            throw new SerializationException(\sprintf('The given data could not be serialized: %s', $exception->getMessage()), 0, $exception);
        } catch (\Error $exception) {
            throw new SerializationException(\sprintf('The given data could not be serialized: %s', $exception->getMessage()), 0, $exception);
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        try {
            $result = \unserialize($data, ['allowed_classes' => isset($this->allowedClasses) ? $this->allowedClasses : \true]);
            if ($result === \false && $data !== \serialize(\false)) {
                throw new SerializationException('Invalid data provided to unserialize: ' . encodeUnprintableChars($data));
            }
        } catch (\Exception $exception) {
            throw new SerializationException('Exception thrown when unserializing data', 0, $exception);
        } catch (\Error $exception) {
            throw new SerializationException('Exception thrown when unserializing data', 0, $exception);
        }
        return $result;
    }
}
