<?php

namespace Phabel\PhpParser\Node\Scalar;

use Phabel\PhpParser\Node\Scalar;
class DNumber extends Scalar
{
    /** @var float Number value */
    public $value;
    /**
     * Constructs a float number scalar node.
     *
     * @param float $value      Value of the number
     * @param array $attributes Additional attributes
     */
    public function __construct($value, array $attributes = [])
    {
        if (!\is_float($value)) {
            if (!(\is_bool($value) || \is_numeric($value))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $value = (double) $value;
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
     * @internal
     *
     * Parses a DNUMBER token like PHP would.
     *
     * @param string $str A string number
     *
     * @return float The parsed number
     */
    public static function parse($str)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        $str = \str_replace('_', '', $str);
        // if string contains any of .eE just cast it to float
        if (\false !== \strpbrk($str, '.eE')) {
            $phabelReturn = (float) $str;
            if (!\is_float($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (double) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        // otherwise it's an integer notation that overflowed into a float
        // if it starts with 0 it's one of the special integer notations
        if ('0' === $str[0]) {
            // hex
            if ('x' === $str[1] || 'X' === $str[1]) {
                $phabelReturn = \hexdec($str);
                if (!\is_float($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (double) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            // bin
            if ('b' === $str[1] || 'B' === $str[1]) {
                $phabelReturn = \bindec($str);
                if (!\is_float($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (double) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            $phabelReturn = \octdec(\substr($str, 0, \strcspn($str, '89')));
            if (!\is_float($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (double) $phabelReturn;
                }
            }
            // oct
            // substr($str, 0, strcspn($str, '89')) cuts the string at the first invalid digit (8 or 9)
            // so that only the digits before that are used
            return $phabelReturn;
        }
        $phabelReturn = (float) $str;
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        // dec
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Scalar_DNumber';
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
