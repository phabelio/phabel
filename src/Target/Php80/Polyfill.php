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
    public static function easter_date(?int $year = null, int $mode = CAL_EASTER_DEFAULT): int
    {
        $year = $year ?? (int) \date('Y');
        return easter_date($year, $mode);
    }
    public static function easter_days(?int $year = null, int $mode = CAL_EASTER_DEFAULT): int
    {
        $year = $year ?? (int) \date('Y');
        return easter_days($year, $mode);
    }
    public static function unixtojd(?int $timestamp = null)
    {
        $timestamp = $timestamp ?? \time();
        $phabelReturn = unixtojd($timestamp);
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
    public static function date_sunrise(int $timestamp, int $returnFormat = SUNFUNCS_RET_STRING, ?float $latitude = null, ?float $longitude = null, ?float $zenith = null, ?float $utcOffset = null)
    {
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
    public static function date_sunset(int $timestamp, int $returnFormat = SUNFUNCS_RET_STRING, ?float $latitude = null, ?float $longitude = null, ?float $zenith = null, ?float $utcOffset = null)
    {
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
    public static function date(string $format, ?int $timestamp = null): string
    {
        return \date($format, $timestamp ?? \time());
    }
    public static function getdate(?int $timestamp = null): array
    {
        return \getdate($timestamp ?? \time());
    }
    public static function gmdate(string $format, ?int $timestamp = null): string
    {
        return \gmdate($format, $timestamp ?? \time());
    }
    public static function gmstrftime(string $format, ?int $timestamp = null)
    {
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
    public static function idate(string $format, ?int $timestamp = null)
    {
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
    public static function localtime(?int $timestamp = null, bool $associative = false): array
    {
        return \localtime($timestamp ?? \time(), $associative);
    }
    public static function strftime(string $format, ?int $timestamp = null)
    {
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
    public static function strtotime(string $datetime, ?int $baseTimestamp = null)
    {
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
    public static function error_reporting(?int $error_level = null): int
    {
        return $error_level === null ? \error_reporting() : \error_reporting($error_level);
    }
    public static function hash_update_file($context, string $filename, $stream_context = null): bool
    {
        return $stream_context ? \hash_update_file($context, $filename, $stream_context) : \hash_update_file($context, $filename);
    }
    public static function iconv_mime_decode_headers(string $headers, int $mode = 0, ?string $encoding = null)
    {
        $phabelReturn = \iconv_mime_decode_headers($headers, $mode, $encoding ?? \iconv_get_encoding('internal_encoding'));
        if (!(\is_array($phabelReturn) || $phabelReturn instanceof false)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type false|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function iconv_mime_decode(string $string, int $mode = 0, ?string $encoding = null)
    {
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
    public static function iconv_strlen(string $string, ?string $encoding = null)
    {
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
    public static function iconv_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null)
    {
        $phabelReturn = \iconv_strpos($haystack, $needle, $offset, $encoding ?? \iconv_get_encoding('internal_encoding'));
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
    public static function iconv_strrpos(string $haystack, string $needle, ?string $encoding = null)
    {
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
    public static function iconv_substr(string $string, int $offset, ?int $length = null, ?string $encoding = null)
    {
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
    public static function get_resources(?string $type = null): array
    {
        return $type === null ? \get_resources() : \get_resources($type);
    }
    public static function mhash(int $algo, string $data, ?string $key = null)
    {
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
    public static function ignore_user_abort(?bool $enable = null): int
    {
        return $enable === null ? \ignore_user_abort() : \ignore_user_abort($enable);
    }
    public static function fsockopen(string $hostname, int $port = -1, int &$error_code = null, string &$error_message = null, ?float $timeout = null)
    {
        return $timeout === null ? \fsockopen($hostname, $port, $error_code, $error_message) : \fsockopen($hostname, $port, $error_code, $error_message);
    }
    public static function ob_implicit_flush(bool $flag = true): void
    {
        \ob_implicit_flush((int) $flag);
    }
    public static function password_hash(string $password, $algo, array $options = []): string
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
        return \password_hash($password, $algo ?? PASSWORD_DEFAULT, $options);
    }
    public static function pcntl_async_signals(?bool $enable = null): bool
    {
        return $enable === null ? \pcntl_async_signals() : \pcntl_async_signals($enable);
    }
    public static function pcntl_getpriority(?int $process_id = null, int $mode = PRIO_PROCESS)
    {
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
    public static function pcntl_setpriority(int $priority, ?int $process_id = null, int $mode = PRIO_PROCESS): bool
    {
        return \pcntl_setpriority($priority, $process_id ?? \getmypid(), $mode);
    }
    public static function readline_info(?string $var_name = null, $value = null)
    {
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
    public static function readline_read_history(?string $filename = null): bool
    {
        return $filename === null ? \readline_read_history() : \readline_read_history($filename);
    }
    public static function readline_write_history(?string $filename = null): bool
    {
        return $filename === null ? \readline_read_history() : \readline_write_history($filename);
    }
    public static function session_cache_expire(?int $value = null)
    {
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
    public static function session_cache_limiter(?string $value = null)
    {
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
    public static function session_id(?string $id = null)
    {
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
    public static function session_module_name(?string $module = null)
    {
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
    public static function session_name(?string $name = null)
    {
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
    public static function session_save_path(?string $path = null)
    {
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
    public static function session_set_cookie_params($lifetime_or_options, ?string $path = null, ?string $domain = null, ?bool $secure = null, ?bool $httponly = null): bool
    {
        if (!\is_array($lifetime_or_options)) {
            if (!\is_int($lifetime_or_options)) {
                if (!(\is_bool($lifetime_or_options) || \is_numeric($lifetime_or_options))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($lifetime_or_options) must be of type int|array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lifetime_or_options) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $lifetime_or_options = (int) $lifetime_or_options;
            }
        }
        return \is_array($lifetime_or_options) ? \session_set_cookie_params($lifetime_or_options) : \session_set_cookie_params(\Phabel\Target\Php74\Polyfill::array_filter(['lifetime' => $lifetime_or_options, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly]));
    }
    public static function spl_autoload_extensions(?string $file_extensions = null): string
    {
        return $file_extensions === null ? \spl_autoload_extensions() : \spl_autoload_extensions($file_extensions);
    }
    public static function spl_autoload_register(?callable $callback = null, bool $throw = true, bool $prepend = false): bool
    {
        return \spl_autoload_register($callback ?? 'spl_autoload', $throw, $prepend);
    }
    public static function spl_autoload(string $class, ?string $file_extensions = null): void
    {
        $file_extensions === null ? \spl_autoload($class) : \spl_autoload($class, $file_extensions);
    }
    public static function html_entity_decode(string $string, int $flags = ENT_COMPAT, ?string $encoding = null): string
    {
        return \html_entity_decode($string, $flags, $encoding ?? Tools::ini_get('default_charset'));
    }
    public static function htmlentities(string $string, int $flags = ENT_COMPAT, ?string $encoding = null, bool $double_encode = true): string
    {
        return \htmlentities($string, $flags, $encoding ?? Tools::ini_get('default_charset'), $double_encode);
    }
    public static function str_word_count(string $string, int $format = 0, ?string $characters = null)
    {
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
    public static function strcspn(string $string, string $characters, int $offset = 0, ?int $length = null): int
    {
        return $length === null ? \strcspn($string, $characters, $offset) : \strcspn($string, $characters, $offset, $length);
    }
    public static function strip_tags(string $string, $allowed_tags = null): string
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
    public static function strspn(string $string, string $characters, int $offset = 0, ?int $length = null): int
    {
        return $length === null ? \strspn($string, $characters, $offset) : \strspn($string, $characters, $offset, $length);
    }
    public static function substr_compare(string $haystack, string $needle, int $offset, ?int $length = null, bool $case_insensitive = false): int
    {
        return \substr_compare($haystack, $needle, $offset, $length ?? \max(\strlen($needle), \strlen($haystack) - ($offset < 0 ? \strlen($haystack) + $offset : $offset)), $case_insensitive);
    }
    public static function substr_count(string $haystack, string $needle, int $offset = 0, ?int $length = null): int
    {
        return $length === null ? \substr_count($haystack, $needle, $offset) : \substr_count($haystack, $needle, $offset, $length);
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
    public static function substr(string $string, int $offset, ?int $length = null): string
    {
        return $length === null ? \substr($string, $offset) : \substr($string, $offset, $length);
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config): array
    {
        return [TargetPolyfill::class => [self::class => true]];
    }
}
