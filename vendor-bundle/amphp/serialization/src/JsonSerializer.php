<?php

namespace Phabel\Amp\Serialization;

final class JsonSerializer implements Serializer
{
    // Equal to JSON_THROW_ON_ERROR, only available in PHP 7.3+.
    const THROW_ON_ERROR = 4194304;
    /** @var bool */
    private $associative;
    /** @var int */
    private $encodeOptions;
    /** @var int */
    private $decodeOptions;
    /** @var int */
    private $depth;
    /**
     * Creates a JSON serializer that decodes objects to associative arrays.
     *
     * @param int $encodeOptions @see \json_encode() options parameter.
     * @param int $decodeOptions @see \json_decode() options parameter.
     * @param int $depth Maximum recursion depth.
     *
     * @return self
     */
    public static function withAssociativeArrays($encodeOptions = 0, $decodeOptions = 0, $depth = 512)
    {
        if (!\is_int($encodeOptions)) {
            if (!(\is_bool($encodeOptions) || \is_numeric($encodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($encodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $encodeOptions = (int) $encodeOptions;
            }
        }
        if (!\is_int($decodeOptions)) {
            if (!(\is_bool($decodeOptions) || \is_numeric($decodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($decodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decodeOptions = (int) $decodeOptions;
            }
        }
        if (!\is_int($depth)) {
            if (!(\is_bool($depth) || \is_numeric($depth))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($depth) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($depth) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $depth = (int) $depth;
            }
        }
        $phabelReturn = new self(\true, $encodeOptions, $decodeOptions, $depth);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Creates a JSON serializer that decodes objects to instances of stdClass.
     *
     * @param int $encodeOptions @see \json_encode() options parameter.
     * @param int $decodeOptions @see \json_decode() options parameter.
     * @param int $depth Maximum recursion depth.
     *
     * @return self
     */
    public static function withObjects($encodeOptions = 0, $decodeOptions = 0, $depth = 512)
    {
        if (!\is_int($encodeOptions)) {
            if (!(\is_bool($encodeOptions) || \is_numeric($encodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($encodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $encodeOptions = (int) $encodeOptions;
            }
        }
        if (!\is_int($decodeOptions)) {
            if (!(\is_bool($decodeOptions) || \is_numeric($decodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($decodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decodeOptions = (int) $decodeOptions;
            }
        }
        if (!\is_int($depth)) {
            if (!(\is_bool($depth) || \is_numeric($depth))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($depth) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($depth) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $depth = (int) $depth;
            }
        }
        $phabelReturn = new self(\false, $encodeOptions, $decodeOptions, $depth);
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function __construct($associative, $encodeOptions = 0, $decodeOptions = 0, $depth = 512)
    {
        if (!\is_bool($associative)) {
            if (!(\is_bool($associative) || \is_numeric($associative) || \is_string($associative))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($associative) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($associative) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $associative = (bool) $associative;
            }
        }
        if (!\is_int($encodeOptions)) {
            if (!(\is_bool($encodeOptions) || \is_numeric($encodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($encodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $encodeOptions = (int) $encodeOptions;
            }
        }
        if (!\is_int($decodeOptions)) {
            if (!(\is_bool($decodeOptions) || \is_numeric($decodeOptions))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($decodeOptions) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decodeOptions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $decodeOptions = (int) $decodeOptions;
            }
        }
        if (!\is_int($depth)) {
            if (!(\is_bool($depth) || \is_numeric($depth))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($depth) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($depth) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $depth = (int) $depth;
            }
        }
        $this->associative = $associative;
        $this->depth = $depth;
        // We don't want to throw on errors, we manually check for errors using json_last_error().
        $this->encodeOptions = $encodeOptions & ~self::THROW_ON_ERROR;
        $this->decodeOptions = $decodeOptions & ~self::THROW_ON_ERROR;
    }
    public function serialize($data)
    {
        $result = \json_encode($data, $this->encodeOptions, $this->depth);
        switch ($code = \json_last_error()) {
            case \JSON_ERROR_NONE:
                $phabelReturn = $result;
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            default:
                throw new SerializationException(\json_last_error_msg(), $code);
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
        $result = \json_decode($data, $this->associative, $this->depth, $this->decodeOptions);
        switch ($code = \json_last_error()) {
            case \JSON_ERROR_NONE:
                return $result;
            default:
                throw new SerializationException(\json_last_error_msg(), $code);
        }
    }
}
