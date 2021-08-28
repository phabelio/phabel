<?php

namespace Phabel\PhpParser\Node\Scalar;

use Phabel\PhpParser\Error;
use Phabel\PhpParser\Node\Scalar;
class String_ extends Scalar
{
    /* For use in "kind" attribute */
    const KIND_SINGLE_QUOTED = 1;
    const KIND_DOUBLE_QUOTED = 2;
    const KIND_HEREDOC = 3;
    const KIND_NOWDOC = 4;
    /** @var string String value */
    public $value;
    protected static $replacements = ['\\' => '\\', '$' => '$', 'n' => "\n", 'r' => "\r", 't' => "\t", 'f' => "\f", 'v' => "\v", 'e' => "\x1b"];
    /**
     * Constructs a string scalar node.
     *
     * @param string $value      Value of the string
     * @param array  $attributes Additional attributes
     */
    public function __construct($value, array $attributes = [])
    {
        if (!\is_string($value)) {
            if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $value = (string) $value;
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
     * Parses a string token.
     *
     * @param string $str String token content
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     *
     * @return string The parsed string
     */
    public static function parse($str, $parseUnicodeEscape = \true)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        if (!\is_bool($parseUnicodeEscape)) {
            if (!(\is_bool($parseUnicodeEscape) || \is_numeric($parseUnicodeEscape) || \is_string($parseUnicodeEscape))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($parseUnicodeEscape) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($parseUnicodeEscape) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $parseUnicodeEscape = (bool) $parseUnicodeEscape;
            }
        }
        $bLength = 0;
        if ('b' === $str[0] || 'B' === $str[0]) {
            $bLength = 1;
        }
        if ('\'' === $str[$bLength]) {
            $phabelReturn = \str_replace(['\\\\', '\\\''], ['\\', '\''], \substr($str, $bLength + 1, -1));
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        } else {
            $phabelReturn = self::parseEscapeSequences(\substr($str, $bLength + 1, -1), '"', $parseUnicodeEscape);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    /**
     * @internal
     *
     * Parses escape sequences in strings (all string types apart from single quoted).
     *
     * @param string      $str   String without quotes
     * @param null|string $quote Quote type
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     *
     * @return string String with escape sequences parsed
     */
    public static function parseEscapeSequences($str, $quote, $parseUnicodeEscape = \true)
    {
        if (!\is_string($str)) {
            if (!(\is_string($str) || \is_object($str) && \method_exists($str, '__toString') || (\is_bool($str) || \is_numeric($str)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($str) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($str) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $str = (string) $str;
            }
        }
        if (!\is_bool($parseUnicodeEscape)) {
            if (!(\is_bool($parseUnicodeEscape) || \is_numeric($parseUnicodeEscape) || \is_string($parseUnicodeEscape))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($parseUnicodeEscape) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($parseUnicodeEscape) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $parseUnicodeEscape = (bool) $parseUnicodeEscape;
            }
        }
        if (null !== $quote) {
            $str = \str_replace('\\' . $quote, $quote, $str);
        }
        $extra = '';
        if ($parseUnicodeEscape) {
            $extra = '|u\\{([0-9a-fA-F]+)\\}';
        }
        $phabelReturn = \preg_replace_callback('~\\\\([\\\\$nrtfve]|[xX][0-9a-fA-F]{1,2}|[0-7]{1,3}' . $extra . ')~', function ($matches) {
            $str = $matches[1];
            if (isset(self::$replacements[$str])) {
                return self::$replacements[$str];
            } elseif ('x' === $str[0] || 'X' === $str[0]) {
                return \chr(\hexdec(\substr($str, 1)));
            } elseif ('u' === $str[0]) {
                return self::codePointToUtf8(\hexdec($matches[2]));
            } else {
                return \chr(\octdec($str));
            }
        }, $str);
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
     * Converts a Unicode code point to its UTF-8 encoded representation.
     *
     * @param int $num Code point
     *
     * @return string UTF-8 representation of code point
     */
    private static function codePointToUtf8($num)
    {
        if (!\is_int($num)) {
            if (!(\is_bool($num) || \is_numeric($num))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($num) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($num) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $num = (int) $num;
            }
        }
        if ($num <= 0x7f) {
            $phabelReturn = \chr($num);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if ($num <= 0x7ff) {
            $phabelReturn = \chr(($num >> 6) + 0xc0) . \chr(($num & 0x3f) + 0x80);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if ($num <= 0xffff) {
            $phabelReturn = \chr(($num >> 12) + 0xe0) . \chr(($num >> 6 & 0x3f) + 0x80) . \chr(($num & 0x3f) + 0x80);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        if ($num <= 0x1fffff) {
            $phabelReturn = \chr(($num >> 18) + 0xf0) . \chr(($num >> 12 & 0x3f) + 0x80) . \chr(($num >> 6 & 0x3f) + 0x80) . \chr(($num & 0x3f) + 0x80);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        throw new Error('Invalid UTF-8 codepoint escape sequence: Codepoint too large');
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    public function getType()
    {
        $phabelReturn = 'Scalar_String';
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
