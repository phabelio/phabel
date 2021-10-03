<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use Phabel\Target\Polyfill as TargetPolyfill;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    // Todo: getopt
    // Todo: DateInterval, DateTime, DateTimeZone, IntlDateFormatter
    // Todo: filters
    // Todo: hashes w/ phpseclib
    // Todo: mb_ereg functions
    // Todo: grapheme_extract
    // Todo: getenv
    // Skip: output buffer functions
    public static function unpack(string $format, string $string, int $offset = 0)
    {
        $phabelReturn = \unpack($format, \Phabel\Target\Php80\Polyfill::substr($string, $offset));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function long2ip(int $ip)
    {
        $phabelReturn = \long2ip($ip);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (string) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function file_get_contents(...$params)
    {
        if (isset($params[3]) && $params[3] < 0) {
            $f = \fopen($params[0], 'r', $params[1], $params[2]);
            \fseek($f, 0, \SEEK_END);
            \fseek($f, \ftell($f) + $params[3], \SEEK_SET);
            $length = $params[4] ?? null;
            if ($length === null) {
                $phabelReturn = \stream_get_contents($f);
                if (!($phabelReturn === \false)) {
                    if (!\is_string($phabelReturn)) {
                        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
            $phabelReturn = \fread($f, $length);
            if (!($phabelReturn === \false)) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \file_get_contents(...$params);
        if (!($phabelReturn === \false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function get_headers(string $url, int $associative = 0, $context = null)
    {
        if (!$context) {
            $phabelReturn = \get_headers($url, $associative);
            if (!(\is_array($phabelReturn) || $phabelReturn === \false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\file_get_contents($url, \false, $context) === \false) {
            $phabelReturn = \false;
            if (!(\is_array($phabelReturn) || $phabelReturn === \false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$associative) {
            $phabelReturn = $http_response_header;
            if (!(\is_array($phabelReturn) || $phabelReturn === \false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $headers = [$http_response_header[0]];
        foreach ($http_response_header as $i => $header) {
            if ($i) {
                [$k, $v] = \explode(":", $header, 2);
                $headers[\trim($k, ' ')] = \trim($v, ' ');
            }
        }
        $phabelReturn = $headers;
        if (!(\is_array($phabelReturn) || $phabelReturn === \false)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function substr_count(string $haystack, string $needle, int $offset = 0, ?int $length = null) : int
    {
        if ($offset < 0) {
            $offset = \strlen($haystack) + $offset;
        }
        if ($length === null) {
            return \Phabel\Target\Php80\Polyfill::substr_count($haystack, $needle, $offset);
        }
        if ($length === 0) {
            return \Phabel\Target\Php80\Polyfill::substr_count($haystack, $needle, $offset);
        }
        if ($length < 0) {
            $length = \strlen($haystack) - $length;
        }
        return \Phabel\Target\Php80\Polyfill::substr_count($haystack, $needle, $offset);
    }
    public static function strpos(string $haystack, string $needle, int $offset = 0)
    {
        $phabelReturn = \strpos($haystack, $needle, $offset < 0 ? \strlen($haystack) + $offset : $offset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function stripos(string $haystack, string $needle, int $offset = 0)
    {
        $phabelReturn = \stripos($haystack, $needle, $offset < 0 ? \strlen($haystack) + $offset : $offset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function mb_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null)
    {
        $encoding ??= \mb_internal_encoding();
        $phabelReturn = \mb_strpos($haystack, $needle, $offset < 0 ? \mb_strlen($haystack, $encoding) + $offset : $offset, $encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function mb_stripos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null)
    {
        $encoding ??= \mb_internal_encoding();
        $phabelReturn = \mb_stripos($haystack, $needle, $offset < 0 ? \mb_strlen($haystack, $encoding) + $offset : $offset, $encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function mb_strimwidth(string $string, int $start, int $width, string $trim_marker = "", ?string $encoding = null) : string
    {
        $encoding ??= \mb_internal_encoding();
        if ($start < 0) {
            $start = \mb_strlen($string, $encoding) + $start;
        } elseif ($width < 0) {
            $width = \mb_strlen($string, $encoding) + $width;
        }
        return \mb_strimwidth($string, $start, $width, $trim_marker, $encoding);
    }
    public static function iconv_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null)
    {
        $encoding ??= \iconv_get_encoding('internal_encoding');
        $phabelReturn = \Phabel\Target\Php80\Polyfill::iconv_strpos($haystack, $needle, $offset < 0 ? \Phabel\Target\Php80\Polyfill::iconv_strlen($haystack, $encoding) + $offset : $offset, $encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function grapheme_strpos(string $haystack, string $needle, int $offset = 0)
    {
        $phabelReturn = \grapheme_strpos($haystack, $needle, $offset < 0 ? \grapheme_strlen($haystack) + $offset : $offset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function grapheme_stripos(string $haystack, string $needle, int $offset = 0)
    {
        $phabelReturn = \grapheme_stripos($haystack, $needle, $offset < 0 ? \grapheme_strlen($haystack) + $offset : $offset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_int($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type int|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (int) $phabelReturn;
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config) : array
    {
        return [TargetPolyfill::class => [self::class => \true]];
    }
}
