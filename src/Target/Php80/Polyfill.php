<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Target\Polyfill as TargetPolyfill;
use Phabel\Tools;
if (!\defined('CAL_EASTER_DEFAULT')) {
    \define('CAL_EASTER_DEFAULT', 0);
}
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    // Todo: nullability of a bunch of mb_ functions
    public static function easter_date($year = null, int $mode = \CAL_EASTER_DEFAULT) : int
    {
        if (!\is_null($year)) {
            if (!\is_int($year)) {
                if (!(\is_bool($year) || \is_numeric($year))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($year) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($year) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $year = (int) $year;
            }
        }
        $year = $year ?? (int) \date('Y');
        return \easter_date($year, $mode);
    }
    public static function easter_days($year = null, int $mode = \CAL_EASTER_DEFAULT) : int
    {
        if (!\is_null($year)) {
            if (!\is_int($year)) {
                if (!(\is_bool($year) || \is_numeric($year))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($year) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($year) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $year = (int) $year;
            }
        }
        $year = $year ?? (int) \date('Y');
        return \easter_days($year, $mode);
    }
    public static function unixtojd($timestamp = null)
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        $timestamp = $timestamp ?? \time();
        $phabelReturn = \unixtojd($timestamp);
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
    public static function date_sunrise(int $timestamp, int $returnFormat = \SUNFUNCS_RET_STRING, $latitude = null, $longitude = null, $zenith = null, $utcOffset = null)
    {
        if (!\is_null($latitude)) {
            if (!\is_float($latitude)) {
                if (!(\is_bool($latitude) || \is_numeric($latitude))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($latitude) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($latitude) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $latitude = (float) $latitude;
            }
        }
        if (!\is_null($longitude)) {
            if (!\is_float($longitude)) {
                if (!(\is_bool($longitude) || \is_numeric($longitude))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($longitude) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($longitude) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $longitude = (float) $longitude;
            }
        }
        if (!\is_null($zenith)) {
            if (!\is_float($zenith)) {
                if (!(\is_bool($zenith) || \is_numeric($zenith))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($zenith) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($zenith) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $zenith = (float) $zenith;
            }
        }
        if (!\is_null($utcOffset)) {
            if (!\is_float($utcOffset)) {
                if (!(\is_bool($utcOffset) || \is_numeric($utcOffset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #6 ($utcOffset) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($utcOffset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $utcOffset = (float) $utcOffset;
            }
        }
        $latitude = $latitude ?? Tools::ini_get('date.default_latitude');
        $longitude = $longitude ?? Tools::ini_get('date.default_longitude');
        $zenith = $zenith ?? Tools::ini_get('date.sunrise_zenith');
        $utcOffset = $utcOffset ?? 0;
        $phabelReturn = \date_sunrise($timestamp, $returnFormat, $latitude, $longitude, $zenith, $utcOffset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_float($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        if (!\is_int($phabelReturn)) {
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                                if (!\is_string($phabelReturn)) {
                                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|int|float|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                                    }
                                    $phabelReturn = (string) $phabelReturn;
                                }
                            } else {
                                $phabelReturn = (int) $phabelReturn;
                            }
                        }
                    } else {
                        $phabelReturn = (float) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function date_sunset(int $timestamp, int $returnFormat = \SUNFUNCS_RET_STRING, $latitude = null, $longitude = null, $zenith = null, $utcOffset = null)
    {
        if (!\is_null($latitude)) {
            if (!\is_float($latitude)) {
                if (!(\is_bool($latitude) || \is_numeric($latitude))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($latitude) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($latitude) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $latitude = (float) $latitude;
            }
        }
        if (!\is_null($longitude)) {
            if (!\is_float($longitude)) {
                if (!(\is_bool($longitude) || \is_numeric($longitude))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($longitude) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($longitude) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $longitude = (float) $longitude;
            }
        }
        if (!\is_null($zenith)) {
            if (!\is_float($zenith)) {
                if (!(\is_bool($zenith) || \is_numeric($zenith))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($zenith) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($zenith) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $zenith = (float) $zenith;
            }
        }
        if (!\is_null($utcOffset)) {
            if (!\is_float($utcOffset)) {
                if (!(\is_bool($utcOffset) || \is_numeric($utcOffset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #6 ($utcOffset) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($utcOffset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $utcOffset = (float) $utcOffset;
            }
        }
        $latitude = $latitude ?? Tools::ini_get('date.default_latitude');
        $longitude = $longitude ?? Tools::ini_get('date.default_longitude');
        $zenith = $zenith ?? Tools::ini_get('date.sunset_zenith');
        $utcOffset = $utcOffset ?? 0;
        $phabelReturn = \date_sunset($timestamp, $returnFormat, $latitude, $longitude, $zenith, $utcOffset);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_float($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                        if (!\is_int($phabelReturn)) {
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                                if (!\is_string($phabelReturn)) {
                                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string|int|float|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                                    }
                                    $phabelReturn = (string) $phabelReturn;
                                }
                            } else {
                                $phabelReturn = (int) $phabelReturn;
                            }
                        }
                    } else {
                        $phabelReturn = (float) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function date(string $format, $timestamp = null) : string
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        return \date($format, $timestamp ?? \time());
    }
    public static function getdate($timestamp = null) : array
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        return \getdate($timestamp ?? \time());
    }
    public static function gmdate(string $format, $timestamp = null) : string
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        return \gmdate($format, $timestamp ?? \time());
    }
    public static function gmstrftime(string $format, $timestamp = null)
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        $phabelReturn = \gmstrftime($format, $timestamp ?? \time());
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function idate(string $format, $timestamp = null)
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        $phabelReturn = \idate($format, $timestamp ?? \time());
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
    public static function localtime($timestamp = null, bool $associative = \false) : array
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        return \localtime($timestamp ?? \time(), $associative);
    }
    public static function strftime(string $format, $timestamp = null)
    {
        if (!\is_null($timestamp)) {
            if (!\is_int($timestamp)) {
                if (!(\is_bool($timestamp) || \is_numeric($timestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($timestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timestamp = (int) $timestamp;
            }
        }
        $phabelReturn = \strftime($format, $timestamp ?? \time());
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function strtotime(string $datetime, $baseTimestamp = null)
    {
        if (!\is_null($baseTimestamp)) {
            if (!\is_int($baseTimestamp)) {
                if (!(\is_bool($baseTimestamp) || \is_numeric($baseTimestamp))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($baseTimestamp) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($baseTimestamp) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $baseTimestamp = (int) $baseTimestamp;
            }
        }
        $phabelReturn = \strtotime($datetime, $baseTimestamp ?? \time());
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
    public static function error_reporting($error_level = null) : int
    {
        if (!\is_null($error_level)) {
            if (!\is_int($error_level)) {
                if (!(\is_bool($error_level) || \is_numeric($error_level))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($error_level) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($error_level) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $error_level = (int) $error_level;
            }
        }
        return $error_level === null ? \error_reporting() : \error_reporting($error_level);
    }
    public static function hash_update_file($context, string $filename, $stream_context = null) : bool
    {
        return $stream_context ? \hash_update_file($context, $filename, $stream_context) : \hash_update_file($context, $filename);
    }
    public static function iconv_mime_decode_headers(string $headers, int $mode = 0, $encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \iconv_mime_decode_headers($headers, $mode, $encoding ?? \iconv_get_encoding('internal_encoding'));
        if (!(\is_array($phabelReturn) || $phabelReturn instanceof \Phabel\Target\Php80\false)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function iconv_mime_decode(string $string, int $mode = 0, $encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \iconv_mime_decode($string, $mode, $encoding ?? \iconv_get_encoding('internal_encoding'));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function iconv_strlen(string $string, $encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \iconv_strlen($string, $encoding ?? \iconv_get_encoding('internal_encoding'));
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
    public static function iconv_strpos(string $haystack, string $needle, int $offset = 0, $encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \Phabel\Target\Php71\Polyfill::iconv_strpos($haystack, $needle, $offset, $encoding ?? \iconv_get_encoding('internal_encoding'));
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
    public static function iconv_strrpos(string $haystack, string $needle, $encoding = null)
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \iconv_strrpos($haystack, $needle, $encoding ?? \iconv_get_encoding('internal_encoding'));
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
    public static function iconv_substr(string $string, int $offset, $length = null, $encoding = null)
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $phabelReturn = \iconv_substr($string, $offset, $length, $encoding ?? \iconv_get_encoding('internal_encoding'));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function get_resources($type = null) : array
    {
        if (!\is_null($type)) {
            if (!\is_string($type)) {
                if (!(\is_string($type) || \Phabel\Target\Php72\Polyfill::is_object($type) && \method_exists($type, '__toString') || (\is_bool($type) || \is_numeric($type)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $type = (string) $type;
            }
        }
        return $type === null ? \get_resources() : \get_resources($type);
    }
    public static function mhash(int $algo, string $data, $key = null)
    {
        if (!\is_null($key)) {
            if (!\is_string($key)) {
                if (!(\is_string($key) || \Phabel\Target\Php72\Polyfill::is_object($key) && \method_exists($key, '__toString') || (\is_bool($key) || \is_numeric($key)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($key) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $key = (string) $key;
            }
        }
        $phabelReturn = $key === null ? \mhash($algo, $data) : \mhash($algo, $data);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function ignore_user_abort($enable = null) : int
    {
        if (!\is_null($enable)) {
            if (!\is_bool($enable)) {
                if (!(\is_bool($enable) || \is_numeric($enable) || \is_string($enable))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($enable) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($enable) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $enable = (bool) $enable;
            }
        }
        return $enable === null ? \ignore_user_abort() : \ignore_user_abort($enable);
    }
    public static function fsockopen(string $hostname, int $port = -1, int &$error_code = null, string &$error_message = null, $timeout = null)
    {
        if (!\is_null($timeout)) {
            if (!\is_float($timeout)) {
                if (!(\is_bool($timeout) || \is_numeric($timeout))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($timeout) must be of type ?float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($timeout) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $timeout = (float) $timeout;
            }
        }
        return $timeout === null ? \fsockopen($hostname, $port, $error_code, $error_message) : \fsockopen($hostname, $port, $error_code, $error_message);
    }
    public static function ob_implicit_flush(bool $flag = \true)
    {
        \ob_implicit_flush((int) $flag);
    }
    public static function password_hash(string $password, $algo, array $options = []) : string
    {
        if (!\is_null($algo)) {
            if (!\is_int($algo)) {
                if (!(\is_bool($algo) || \is_numeric($algo))) {
                    if (!\is_string($algo)) {
                        if (!(\is_string($algo) || \Phabel\Target\Php72\Polyfill::is_object($algo) && \method_exists($algo, '__toString') || (\is_bool($algo) || \is_numeric($algo)))) {
                            throw new \TypeError(__METHOD__ . '(): Argument #2 ($algo) must be of type string|int|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($algo) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        $algo = (string) $algo;
                    }
                } else {
                    $algo = (int) $algo;
                }
            }
        }
        return \password_hash($password, $algo ?? \PASSWORD_DEFAULT, $options);
    }
    public static function pcntl_async_signals($enable = null) : bool
    {
        if (!\is_null($enable)) {
            if (!\is_bool($enable)) {
                if (!(\is_bool($enable) || \is_numeric($enable) || \is_string($enable))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($enable) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($enable) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $enable = (bool) $enable;
            }
        }
        return $enable === null ? \pcntl_async_signals() : \pcntl_async_signals($enable);
    }
    public static function pcntl_getpriority($process_id = null, int $mode = \PRIO_PROCESS)
    {
        if (!\is_null($process_id)) {
            if (!\is_int($process_id)) {
                if (!(\is_bool($process_id) || \is_numeric($process_id))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($process_id) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($process_id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $process_id = (int) $process_id;
            }
        }
        $phabelReturn = \pcntl_getpriority($process_id ?? \getmypid(), $mode);
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
    public static function pcntl_setpriority(int $priority, $process_id = null, int $mode = \PRIO_PROCESS) : bool
    {
        if (!\is_null($process_id)) {
            if (!\is_int($process_id)) {
                if (!(\is_bool($process_id) || \is_numeric($process_id))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($process_id) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($process_id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $process_id = (int) $process_id;
            }
        }
        return \pcntl_setpriority($priority, $process_id ?? \getmypid(), $mode);
    }
    public static function readline_info($var_name = null, $value = null)
    {
        if (!\is_null($var_name)) {
            if (!\is_string($var_name)) {
                if (!(\is_string($var_name) || \Phabel\Target\Php72\Polyfill::is_object($var_name) && \method_exists($var_name, '__toString') || (\is_bool($var_name) || \is_numeric($var_name)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($var_name) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($var_name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $var_name = (string) $var_name;
            }
        }
        if (!(\is_null($value) || \is_null($value))) {
            if (!\is_bool($value)) {
                if (!(\is_bool($value) || \is_numeric($value) || \is_string($value))) {
                    if (!\is_string($value)) {
                        if (!(\is_string($value) || \Phabel\Target\Php72\Polyfill::is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                            if (!\is_int($value)) {
                                if (!(\is_bool($value) || \is_numeric($value))) {
                                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($value) must be of type ?int|string|bool|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                                }
                                $value = (int) $value;
                            }
                        } else {
                            $value = (string) $value;
                        }
                    }
                } else {
                    $value = (bool) $value;
                }
            }
        }
        return $var_name === null && $value === null ? \readline_info() : \readline_info($var_name, $value);
    }
    public static function readline_read_history($filename = null) : bool
    {
        if (!\is_null($filename)) {
            if (!\is_string($filename)) {
                if (!(\is_string($filename) || \Phabel\Target\Php72\Polyfill::is_object($filename) && \method_exists($filename, '__toString') || (\is_bool($filename) || \is_numeric($filename)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($filename) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($filename) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $filename = (string) $filename;
            }
        }
        return $filename === null ? \readline_read_history() : \readline_read_history($filename);
    }
    public static function readline_write_history($filename = null) : bool
    {
        if (!\is_null($filename)) {
            if (!\is_string($filename)) {
                if (!(\is_string($filename) || \Phabel\Target\Php72\Polyfill::is_object($filename) && \method_exists($filename, '__toString') || (\is_bool($filename) || \is_numeric($filename)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($filename) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($filename) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $filename = (string) $filename;
            }
        }
        return $filename === null ? \readline_read_history() : \readline_write_history($filename);
    }
    public static function session_cache_expire($value = null)
    {
        if (!\is_null($value)) {
            if (!\is_int($value)) {
                if (!(\is_bool($value) || \is_numeric($value))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $value = (int) $value;
            }
        }
        $phabelReturn = $value === null ? \session_cache_expire() : \session_cache_expire($value);
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
    public static function session_cache_limiter($value = null)
    {
        if (!\is_null($value)) {
            if (!\is_string($value)) {
                if (!(\is_string($value) || \Phabel\Target\Php72\Polyfill::is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $value = (string) $value;
            }
        }
        $phabelReturn = $value === null ? \session_cache_limiter() : \session_cache_limiter($value);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function session_id($id = null)
    {
        if (!\is_null($id)) {
            if (!\is_string($id)) {
                if (!(\is_string($id) || \Phabel\Target\Php72\Polyfill::is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $id = (string) $id;
            }
        }
        $phabelReturn = $id === null ? \session_id() : \session_id($id);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function session_module_name($module = null)
    {
        if (!\is_null($module)) {
            if (!\is_string($module)) {
                if (!(\is_string($module) || \Phabel\Target\Php72\Polyfill::is_object($module) && \method_exists($module, '__toString') || (\is_bool($module) || \is_numeric($module)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($module) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($module) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $module = (string) $module;
            }
        }
        $phabelReturn = $module === null ? \session_module_name() : \session_module_name($module);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function session_name($name = null)
    {
        if (!\is_null($name)) {
            if (!\is_string($name)) {
                if (!(\is_string($name) || \Phabel\Target\Php72\Polyfill::is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $name = (string) $name;
            }
        }
        $phabelReturn = $name === null ? \session_name() : \session_name($name);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function session_save_path($path = null)
    {
        if (!\is_null($path)) {
            if (!\is_string($path)) {
                if (!(\is_string($path) || \Phabel\Target\Php72\Polyfill::is_object($path) && \method_exists($path, '__toString') || (\is_bool($path) || \is_numeric($path)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($path) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($path) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $path = (string) $path;
            }
        }
        $phabelReturn = $path === null ? \session_save_path() : \session_save_path($path);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
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
    public static function session_set_cookie_params($lifetime_or_options, $path = null, $domain = null, $secure = null, $httponly = null) : bool
    {
        if (!\is_array($lifetime_or_options)) {
            if (!\is_int($lifetime_or_options)) {
                if (!(\is_bool($lifetime_or_options) || \is_numeric($lifetime_or_options))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($lifetime_or_options) must be of type int|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lifetime_or_options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $lifetime_or_options = (int) $lifetime_or_options;
            }
        }
        if (!\is_null($path)) {
            if (!\is_string($path)) {
                if (!(\is_string($path) || \Phabel\Target\Php72\Polyfill::is_object($path) && \method_exists($path, '__toString') || (\is_bool($path) || \is_numeric($path)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($path) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($path) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $path = (string) $path;
            }
        }
        if (!\is_null($domain)) {
            if (!\is_string($domain)) {
                if (!(\is_string($domain) || \Phabel\Target\Php72\Polyfill::is_object($domain) && \method_exists($domain, '__toString') || (\is_bool($domain) || \is_numeric($domain)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($domain) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($domain) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $domain = (string) $domain;
            }
        }
        if (!\is_null($secure)) {
            if (!\is_bool($secure)) {
                if (!(\is_bool($secure) || \is_numeric($secure) || \is_string($secure))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($secure) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($secure) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $secure = (bool) $secure;
            }
        }
        if (!\is_null($httponly)) {
            if (!\is_bool($httponly)) {
                if (!(\is_bool($httponly) || \is_numeric($httponly) || \is_string($httponly))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #5 ($httponly) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($httponly) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $httponly = (bool) $httponly;
            }
        }
        return \is_array($lifetime_or_options) ? \session_set_cookie_params($lifetime_or_options) : \session_set_cookie_params(\Phabel\Target\Php74\Polyfill::array_filter(['lifetime' => $lifetime_or_options, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly]));
    }
    public static function spl_autoload_extensions($file_extensions = null) : string
    {
        if (!\is_null($file_extensions)) {
            if (!\is_string($file_extensions)) {
                if (!(\is_string($file_extensions) || \Phabel\Target\Php72\Polyfill::is_object($file_extensions) && \method_exists($file_extensions, '__toString') || (\is_bool($file_extensions) || \is_numeric($file_extensions)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($file_extensions) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file_extensions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $file_extensions = (string) $file_extensions;
            }
        }
        return $file_extensions === null ? \spl_autoload_extensions() : \spl_autoload_extensions($file_extensions);
    }
    public static function spl_autoload_register($callback = null, bool $throw = \true, bool $prepend = \false) : bool
    {
        if (!(\is_callable($callback) || \is_null($callback))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($callback) must be of type ?callable, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($callback) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return \spl_autoload_register($callback ?? 'spl_autoload', $throw, $prepend);
    }
    public static function spl_autoload(string $class, $file_extensions = null)
    {
        if (!\is_null($file_extensions)) {
            if (!\is_string($file_extensions)) {
                if (!(\is_string($file_extensions) || \Phabel\Target\Php72\Polyfill::is_object($file_extensions) && \method_exists($file_extensions, '__toString') || (\is_bool($file_extensions) || \is_numeric($file_extensions)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($file_extensions) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file_extensions) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $file_extensions = (string) $file_extensions;
            }
        }
        $file_extensions === null ? \spl_autoload($class) : \spl_autoload($class, $file_extensions);
    }
    public static function html_entity_decode(string $string, int $flags = \ENT_COMPAT, $encoding = null) : string
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        return \html_entity_decode($string, $flags, $encoding ?? Tools::ini_get('default_charset'));
    }
    public static function htmlentities(string $string, int $flags = \ENT_COMPAT, $encoding = null, bool $double_encode = \true) : string
    {
        if (!\is_null($encoding)) {
            if (!\is_string($encoding)) {
                if (!(\is_string($encoding) || \Phabel\Target\Php72\Polyfill::is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        return \htmlentities($string, $flags, $encoding ?? Tools::ini_get('default_charset'), $double_encode);
    }
    public static function str_word_count(string $string, int $format = 0, $characters = null)
    {
        if (!\is_null($characters)) {
            if (!\is_string($characters)) {
                if (!(\is_string($characters) || \Phabel\Target\Php72\Polyfill::is_object($characters) && \method_exists($characters, '__toString') || (\is_bool($characters) || \is_numeric($characters)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($characters) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($characters) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $characters = (string) $characters;
            }
        }
        $phabelReturn = $format === null ? \str_word_count($string, $format) : \str_word_count($string, $format, $characters);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                if (!\is_array($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type array|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function strcspn(string $string, string $characters, int $offset = 0, $length = null) : int
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        return $length === null ? \strcspn($string, $characters, $offset) : \strcspn($string, $characters, $offset, $length);
    }
    public static function strip_tags(string $string, $allowed_tags = null) : string
    {
        if (!(\is_null($allowed_tags) || \is_null($allowed_tags))) {
            if (!\is_string($allowed_tags)) {
                if (!(\is_string($allowed_tags) || \Phabel\Target\Php72\Polyfill::is_object($allowed_tags) && \method_exists($allowed_tags, '__toString') || (\is_bool($allowed_tags) || \is_numeric($allowed_tags)))) {
                    if (!\is_array($allowed_tags)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #2 ($allowed_tags) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowed_tags) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $allowed_tags = (string) $allowed_tags;
                }
            }
        }
        return $allowed_tags === null ? \strip_tags($string) : \strip_tags($string, $allowed_tags);
    }
    public static function strspn(string $string, string $characters, int $offset = 0, $length = null) : int
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        return $length === null ? \strspn($string, $characters, $offset) : \strspn($string, $characters, $offset, $length);
    }
    public static function substr_compare(string $haystack, string $needle, int $offset, $length = null, bool $case_insensitive = \false) : int
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        return \substr_compare($haystack, $needle, $offset, $length ?? \max(\strlen($needle), \strlen($haystack) - ($offset < 0 ? \strlen($haystack) + $offset : $offset)), $case_insensitive);
    }
    public static function substr_count(string $haystack, string $needle, int $offset = 0, $length = null) : int
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        return $length === null ? \Phabel\Target\Php71\Polyfill::substr_count($haystack, $needle, $offset) : \Phabel\Target\Php71\Polyfill::substr_count($haystack, $needle, $offset, $length);
    }
    public static function substr_replace($string, $replace, $offset, $length = null)
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                if (!\is_array($string)) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type array|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $string = (string) $string;
            }
        }
        if (!\is_string($replace)) {
            if (!(\is_string($replace) || \Phabel\Target\Php72\Polyfill::is_object($replace) && \method_exists($replace, '__toString') || (\is_bool($replace) || \is_numeric($replace)))) {
                if (!\is_array($replace)) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($replace) must be of type array|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($replace) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $replace = (string) $replace;
            }
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                if (!\is_array($offset)) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type array|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $offset = (int) $offset;
            }
        }
        if (!(\is_null($length) || \is_null($length))) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    if (!\is_array($length)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #4 ($length) must be of type ?array|int|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $length = (int) $length;
                }
            }
        }
        $phabelReturn = $length === null ? \substr_replace($string, $replace, $offset) : \substr_replace($string, $replace, $offset, $length);
        if (!\is_array($phabelReturn)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function substr(string $string, int $offset, $length = null) : string
    {
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        return $length === null ? \substr($string, $offset) : \substr($string, $offset, $length);
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config) : array
    {
        return [TargetPolyfill::class => [self::class => \true]];
    }
}
