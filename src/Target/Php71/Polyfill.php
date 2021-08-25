<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;

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
    // Skip: output buffer functions

    public function getenv($params): string|false|array
    {
        if (\count($params) === 0) {
        }
        return \getenv(...$params);
    }

    public static function unpack(string $format, string $string, int $offset = 0): array|bool
    {
        return \unpack($format, \substr($string, $offset));
    }
    public static function long2ip(int $ip): string|bool
    {
        return \long2ip($ip);
    }

    public static function file_get_contents(...$params): string|false
    {
        if (isset($params[3]) && $params[3] < 0) {
            $f = \fopen($params[0], 'r', $params[1], $params[2]);
            \fseek($f, 0, SEEK_END);
            \fseek($f, \ftell($f)+$params[3], SEEK_SET);
            $length = $params[4] ?? null;
            if ($length === null) {
                return \stream_get_contents($f);
            }
            return \fread($f, $length);
        }
        return \file_get_contents(...$params);
    }

    public static function get_headers(string $url, int $associative = 0, $context = null): array|false
    {
        if (!$context) {
            return \get_headers($url, $associative);
        }
        if (\file_get_contents($url, false, $context) === false) {
            return false;
        }
        if (!$associative) {
            return $http_response_header;
        }
        $headers = [$http_response_header[0]];
        foreach ($http_response_header as $i => $header) {
            if ($i) {
                [$k, $v] = \explode(":", $header, 2);
                $headers[\trim($k, ' ')] = \trim($v, ' ');
            }
        }
        return $headers;
    }

    public static function substr_count(string $haystack, string $needle, int $offset = 0, int $length = 0): int
    {
        if ($offset < 0) {
            $offset = \strlen($haystack) + $offset;
        }
        if ($length === 0) {
            return \substr_count($haystack, $needle, $offset);
        }
        if ($length < 0) {
            $length = \strlen($haystack) - $length;
        }
        return \substr_count($haystack, $needle, $offset);
    }

    public static function strpos(string $haystack, string $needle, int $offset = 0): int|bool
    {
        return \strpos($haystack, $needle, $offset < 0 ? \strlen($haystack) + $offset : $offset);
    }
    public static function stripos(string $haystack, string $needle, int $offset = 0): int|bool
    {
        return \stripos($haystack, $needle, $offset < 0 ? \strlen($haystack) + $offset : $offset);
    }
    public static function mb_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null): int|bool
    {
        $encoding ??= \mb_internal_encoding();
        return \mb_strpos($haystack, $needle, $offset < 0 ? \mb_strlen($haystack, $encoding) + $offset : $offset, $encoding);
    }
    public static function mb_stripos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null): int|bool
    {
        $encoding ??= \mb_internal_encoding();
        return \mb_stripos($haystack, $needle, $offset < 0 ? \mb_strlen($haystack, $encoding) + $offset : $offset, $encoding);
    }
    public static function mb_strimwidth(string $string, int $start, int $width, string $trim_marker = "", ?string $encoding = null): string
    {
        $encoding ??= \mb_internal_encoding();
        if ($start < 0) {
            $start = \mb_strlen($string, $encoding) + $start;
        } elseif ($width < 0) {
            $width = \mb_strlen($string, $encoding) + $width;
        }
        return \mb_strimwidth($string, $start, $width, $trim_marker, $encoding);
    }
    public static function iconv_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null): int|bool
    {
        $encoding ??= \iconv_get_encoding('internal_encoding');
        return \iconv_strpos($haystack, $needle, $offset < 0 ? \iconv_strlen($haystack, $encoding) + $offset : $offset, $encoding);
    }
    public static function grapheme_strpos(string $haystack, string $needle, int $offset = 0): int|bool
    {
        return \grapheme_strpos($haystack, $needle, $offset < 0 ? \grapheme_strlen($haystack) + $offset : $offset);
    }
    public static function grapheme_stripos(string $haystack, string $needle, int $offset = 0): int|bool
    {
        return \grapheme_stripos($haystack, $needle, $offset < 0 ? \grapheme_strlen($haystack) + $offset : $offset);
    }
}
