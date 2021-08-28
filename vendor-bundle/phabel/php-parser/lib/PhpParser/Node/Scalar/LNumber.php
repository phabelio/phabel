<?php

namespace Phabel\PhpParser\Node\Scalar;

use Phabel\PhpParser\Error;
use Phabel\PhpParser\Node\Scalar;
class LNumber extends Scalar
{
    /* For use in "kind" attribute */
    const KIND_BIN = 2;
    const KIND_OCT = 8;
    const KIND_DEC = 10;
    const KIND_HEX = 16;
    /** @var int Number value */
    public $value;
    /**
     * Constructs an integer number scalar node.
     *
     * @param int   $value      Value of the number
     * @param array $attributes Additional attributes
     */
    public function __construct($value, array $attributes = [])
    {
        if (!\is_int($value)) {
            if (!(\is_bool($value) || \is_numeric($value))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $value = (int) $value;
            }
        }
        $this->attributes = $attributes;
        $this->value = $value;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['value'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Constructs an LNumber node from a string number literal.
     *
     * @param string $str               String number literal (decimal, octal, hex or binary)
     * @param array  $attributes        Additional attributes
     * @param bool   $allowInvalidOctal Whether to allow invalid octal numbers (PHP 5)
     *
     * @return LNumber The constructed LNumber, including kind attribute
     */
    public static function fromString($str, array $attributes = [], $allowInvalidOctal = \false)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        if (!\is_bool($allowInvalidOctal)) {
            if (!(\is_bool($allowInvalidOctal) || \is_numeric($allowInvalidOctal) || \is_string($allowInvalidOctal))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($allowInvalidOctal) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowInvalidOctal) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $allowInvalidOctal = (bool) $allowInvalidOctal;
            }
        }
        $str = \str_replace('_', '', $str);
        if ('0' !== $str[0] || '0' === $str) {
            $attributes['kind'] = LNumber::KIND_DEC;
            $phabelReturn = new LNumber((int) $str, $attributes);
            if (!$phabelReturn instanceof LNumber) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type LNumber, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ('x' === $str[1] || 'X' === $str[1]) {
            $attributes['kind'] = LNumber::KIND_HEX;
            $phabelReturn = new LNumber(\hexdec($str), $attributes);
            if (!$phabelReturn instanceof LNumber) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type LNumber, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if ('b' === $str[1] || 'B' === $str[1]) {
            $attributes['kind'] = LNumber::KIND_BIN;
            $phabelReturn = new LNumber(\bindec($str), $attributes);
            if (!$phabelReturn instanceof LNumber) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type LNumber, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$allowInvalidOctal && \strpbrk($str, '89')) {
            throw new Error('Invalid numeric literal', $attributes);
        }
        // use intval instead of octdec to get proper cutting behavior with malformed numbers
        $attributes['kind'] = LNumber::KIND_OCT;
        $phabelReturn = new LNumber(\intval($str, 8), $attributes);
        if (!$phabelReturn instanceof LNumber) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type LNumber, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Scalar_LNumber';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
