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
    public static function unpack($format, $string, $offset = 0)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $format = (string) $format;
        }
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $string = (string) $string;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        $phabelReturn = \unpack($format, \substr($string, $offset));
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
    public static function long2ip($ip)
    {
        if (!\is_int($ip)) {
            if (!(\is_bool($ip) || \is_numeric($ip))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($ip) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($ip) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $ip = (int) $ip;
        }
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
            $length = isset($params[4]) ? $params[4] : null;
            if ($length === null) {
                $phabelReturn = \stream_get_contents($f);
                if (!$phabelReturn instanceof \Phabel\Target\Php71\false) {
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
            if (!$phabelReturn instanceof \Phabel\Target\Php71\false) {
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
        if (!$phabelReturn instanceof \Phabel\Target\Php71\false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function get_headers($url, $associative = 0, $context = null)
    {
        if (!\is_string($url)) {
            if (!(\is_string($url) || \is_object($url) && \method_exists($url, '__toString') || (\is_bool($url) || \is_numeric($url)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($url) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($url) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $url = (string) $url;
        }
        if (!\is_int($associative)) {
            if (!(\is_bool($associative) || \is_numeric($associative))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($associative) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($associative) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $associative = (int) $associative;
        }
        if (!$context) {
            $phabelReturn = \get_headers($url, $associative);
            if (!(\is_array($phabelReturn) || $phabelReturn instanceof \Phabel\Target\Php71\false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\file_get_contents($url, \false, $context) === \false) {
            $phabelReturn = \false;
            if (!(\is_array($phabelReturn) || $phabelReturn instanceof \Phabel\Target\Php71\false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!$associative) {
            $phabelReturn = $http_response_header;
            if (!(\is_array($phabelReturn) || $phabelReturn instanceof \Phabel\Target\Php71\false)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $headers = [$http_response_header[0]];
        foreach ($http_response_header as $i => $header) {
            if ($i) {
                list($k, $v) = \explode(":", $header, 2);
                $headers[\trim($k, ' ')] = \trim($v, ' ');
            }
        }
        $phabelReturn = $headers;
        if (!(\is_array($phabelReturn) || $phabelReturn instanceof \Phabel\Target\Php71\false)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function substr_count($haystack, $needle, $offset = 0, $length = null)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        if ($offset < 0) {
            $offset = \strlen($haystack) + $offset;
        }
        if ($length === null) {
            $phabelReturn = \substr_count($haystack, $needle, $offset);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($length === 0) {
            $phabelReturn = \substr_count($haystack, $needle, $offset);
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($length < 0) {
            $length = \strlen($haystack) - $length;
        }
        $phabelReturn = \substr_count($haystack, $needle, $offset);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function strpos($haystack, $needle, $offset = 0)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
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
    public static function stripos($haystack, $needle, $offset = 0)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
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
    public static function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $encoding = isset($encoding) ? $encoding : \mb_internal_encoding();
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
    public static function mb_stripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $encoding = isset($encoding) ? $encoding : \mb_internal_encoding();
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
    public static function mb_strimwidth($string, $start, $width, $trim_marker = "", $encoding = null)
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $string = (string) $string;
        }
        if (!\is_int($start)) {
            if (!(\is_bool($start) || \is_numeric($start))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($start) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($start) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $start = (int) $start;
        }
        if (!\is_int($width)) {
            if (!(\is_bool($width) || \is_numeric($width))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($width) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($width) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $width = (int) $width;
        }
        if (!\is_string($trim_marker)) {
            if (!(\is_string($trim_marker) || \is_object($trim_marker) && \method_exists($trim_marker, '__toString') || (\is_bool($trim_marker) || \is_numeric($trim_marker)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($trim_marker) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($trim_marker) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $trim_marker = (string) $trim_marker;
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $encoding = isset($encoding) ? $encoding : \mb_internal_encoding();
        if ($start < 0) {
            $start = \mb_strlen($string, $encoding) + $start;
        } elseif ($width < 0) {
            $width = \mb_strlen($string, $encoding) + $width;
        }
        $phabelReturn = \mb_strimwidth($string, $start, $width, $trim_marker, $encoding);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function iconv_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $encoding = isset($encoding) ? $encoding : \iconv_get_encoding('internal_encoding');
        $phabelReturn = \iconv_strpos($haystack, $needle, $offset < 0 ? \iconv_strlen($haystack, $encoding) + $offset : $offset, $encoding);
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
    public static function grapheme_strpos($haystack, $needle, $offset = 0)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
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
    public static function grapheme_stripos($haystack, $needle, $offset = 0)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $haystack = (string) $haystack;
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $needle = (string) $needle;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
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
    public static function withNext(array $config)
    {
        $phabelReturn = [TargetPolyfill::class => [self::class => \true]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
