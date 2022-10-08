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
    public static function easter_date(?int $year = null, int $mode = \CAL_EASTER_DEFAULT) : int
    {
        $year ??= (int) \date('Y');
        return \easter_date($year, $mode);
    }
    public static function easter_days(?int $year = null, int $mode = \CAL_EASTER_DEFAULT) : int
    {
        $year ??= (int) \date('Y');
        return \easter_days($year, $mode);
    }
    public static function unixtojd(?int $timestamp = null) : int|bool
    {
        $timestamp ??= \time();
        return \unixtojd($timestamp);
    }
    public static function date_sunrise(int $timestamp, int $returnFormat = \SUNFUNCS_RET_STRING, ?float $latitude = null, ?float $longitude = null, ?float $zenith = null, ?float $utcOffset = null) : string|int|float|bool
    {
        $latitude ??= Tools::ini_get('date.default_latitude');
        $longitude ??= Tools::ini_get('date.default_longitude');
        $zenith ??= Tools::ini_get('date.sunrise_zenith');
        $utcOffset ??= 0;
        return \date_sunrise($timestamp, $returnFormat, $latitude, $longitude, $zenith, $utcOffset);
    }
    public static function date_sunset(int $timestamp, int $returnFormat = \SUNFUNCS_RET_STRING, ?float $latitude = null, ?float $longitude = null, ?float $zenith = null, ?float $utcOffset = null) : string|int|float|bool
    {
        $latitude ??= Tools::ini_get('date.default_latitude');
        $longitude ??= Tools::ini_get('date.default_longitude');
        $zenith ??= Tools::ini_get('date.sunset_zenith');
        $utcOffset ??= 0;
        return \date_sunset($timestamp, $returnFormat, $latitude, $longitude, $zenith, $utcOffset);
    }
    public static function date(string $format, ?int $timestamp = null) : string
    {
        return \date($format, $timestamp ?? \time());
    }
    public static function getdate(?int $timestamp = null) : array
    {
        return \getdate($timestamp ?? \time());
    }
    public static function gmdate(string $format, ?int $timestamp = null) : string
    {
        return \gmdate($format, $timestamp ?? \time());
    }
    public static function gmstrftime(string $format, ?int $timestamp = null) : string|bool
    {
        return \gmstrftime($format, $timestamp ?? \time());
    }
    public static function idate(string $format, ?int $timestamp = null) : int|bool
    {
        return \idate($format, $timestamp ?? \time());
    }
    public static function localtime(?int $timestamp = null, bool $associative = \false) : array
    {
        return \localtime($timestamp ?? \time(), $associative);
    }
    public static function strftime(string $format, ?int $timestamp = null) : string|bool
    {
        return \strftime($format, $timestamp ?? \time());
    }
    public static function strtotime(string $datetime, ?int $baseTimestamp = null) : int|bool
    {
        return \strtotime($datetime, $baseTimestamp ?? \time());
    }
    public static function error_reporting(?int $error_level = null) : int
    {
        return $error_level === null ? \error_reporting() : \error_reporting($error_level);
    }
    public static function hash_update_file($context, string $filename, $stream_context = null) : bool
    {
        return $stream_context ? \hash_update_file($context, $filename, $stream_context) : \hash_update_file($context, $filename);
    }
    public static function iconv_mime_decode_headers(string $headers, int $mode = 0, ?string $encoding = null) : array|false
    {
        return \iconv_mime_decode_headers($headers, $mode, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function iconv_mime_decode(string $string, int $mode = 0, ?string $encoding = null) : string|bool
    {
        return \iconv_mime_decode($string, $mode, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function iconv_strlen(string $string, ?string $encoding = null) : int|bool
    {
        return \iconv_strlen($string, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function iconv_strpos(string $haystack, string $needle, int $offset = 0, ?string $encoding = null) : int|bool
    {
        return \iconv_strpos($haystack, $needle, $offset, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function iconv_strrpos(string $haystack, string $needle, ?string $encoding = null) : int|bool
    {
        return \iconv_strrpos($haystack, $needle, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function iconv_substr(string $string, int $offset, ?int $length = null, ?string $encoding = null) : string|bool
    {
        return \iconv_substr($string, $offset, $length, $encoding ?? \iconv_get_encoding('internal_encoding'));
    }
    public static function get_resources(?string $type = null) : array
    {
        return $type === null ? \get_resources() : \get_resources($type);
    }
    public static function mhash(int $algo, string $data, ?string $key = null) : string|bool
    {
        return $key === null ? \mhash($algo, $data) : \mhash($algo, $data);
    }
    public static function ignore_user_abort(?bool $enable = null) : int
    {
        return $enable === null ? \ignore_user_abort() : \ignore_user_abort($enable);
    }
    public static function fsockopen(string $hostname, int $port = -1, int &$error_code = null, string &$error_message = null, ?float $timeout = null)
    {
        return $timeout === null ? \fsockopen($hostname, $port, $error_code, $error_message) : \fsockopen($hostname, $port, $error_code, $error_message);
    }
    public static function ob_implicit_flush(bool $flag = \true) : void
    {
        \ob_implicit_flush((int) $flag);
    }
    public static function password_hash(string $password, string|int|null $algo, array $options = []) : string
    {
        return \password_hash($password, $algo ?? \PASSWORD_DEFAULT, $options);
    }
    public static function pcntl_async_signals(?bool $enable = null) : bool
    {
        return $enable === null ? \pcntl_async_signals() : \pcntl_async_signals($enable);
    }
    public static function pcntl_getpriority(?int $process_id = null, int $mode = \PRIO_PROCESS) : int|bool
    {
        return \pcntl_getpriority($process_id ?? \getmypid(), $mode);
    }
    public static function pcntl_setpriority(int $priority, ?int $process_id = null, int $mode = \PRIO_PROCESS) : bool
    {
        return \pcntl_setpriority($priority, $process_id ?? \getmypid(), $mode);
    }
    public static function readline_info(?string $var_name = null, int|string|bool|null $value = null)
    {
        return $var_name === null && $value === null ? \readline_info() : \readline_info($var_name, $value);
    }
    public static function readline_read_history(?string $filename = null) : bool
    {
        return $filename === null ? \readline_read_history() : \readline_read_history($filename);
    }
    public static function readline_write_history(?string $filename = null) : bool
    {
        return $filename === null ? \readline_read_history() : \readline_write_history($filename);
    }
    public static function session_cache_expire(?int $value = null) : int|bool
    {
        return $value === null ? \session_cache_expire() : \session_cache_expire($value);
    }
    public static function session_cache_limiter(?string $value = null) : string|bool
    {
        return $value === null ? \session_cache_limiter() : \session_cache_limiter($value);
    }
    public static function session_id(?string $id = null) : string|bool
    {
        return $id === null ? \session_id() : \session_id($id);
    }
    public static function session_module_name(?string $module = null) : string|bool
    {
        return $module === null ? \session_module_name() : \session_module_name($module);
    }
    public static function session_name(?string $name = null) : string|bool
    {
        return $name === null ? \session_name() : \session_name($name);
    }
    public static function session_save_path(?string $path = null) : string|bool
    {
        return $path === null ? \session_save_path() : \session_save_path($path);
    }
    public static function session_set_cookie_params(int|array $lifetime_or_options, ?string $path = null, ?string $domain = null, ?bool $secure = null, ?bool $httponly = null) : bool
    {
        return \is_array($lifetime_or_options) ? \session_set_cookie_params($lifetime_or_options) : \session_set_cookie_params(\array_filter(['lifetime' => $lifetime_or_options, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly]));
    }
    public static function spl_autoload_extensions(?string $file_extensions = null) : string
    {
        return $file_extensions === null ? \spl_autoload_extensions() : \spl_autoload_extensions($file_extensions);
    }
    public static function spl_autoload_register(?callable $callback = null, bool $throw = \true, bool $prepend = \false) : bool
    {
        return \spl_autoload_register($callback ?? 'spl_autoload', $throw, $prepend);
    }
    public static function spl_autoload(string $class, ?string $file_extensions = null) : void
    {
        $file_extensions === null ? \spl_autoload($class) : \spl_autoload($class, $file_extensions);
    }
    public static function html_entity_decode(string $string, int $flags = \ENT_COMPAT, ?string $encoding = null) : string
    {
        return \html_entity_decode($string, $flags, $encoding ?? Tools::ini_get('default_charset'));
    }
    public static function htmlentities(string $string, int $flags = \ENT_COMPAT, ?string $encoding = null, bool $double_encode = \true) : string
    {
        return \htmlentities($string, $flags, $encoding ?? Tools::ini_get('default_charset'), $double_encode);
    }
    public static function str_word_count(string $string, int $format = 0, ?string $characters = null) : array|int
    {
        return $format === null ? \str_word_count($string, $format) : \str_word_count($string, $format, $characters);
    }
    public static function strcspn(string $string, string $characters, int $offset = 0, ?int $length = null) : int
    {
        return $length === null ? \strcspn($string, $characters, $offset) : \strcspn($string, $characters, $offset, $length);
    }
    public static function strip_tags(string $string, array|string|null $allowed_tags = null) : string
    {
        return $allowed_tags === null ? \strip_tags($string) : \strip_tags($string, $allowed_tags);
    }
    public static function strspn(string $string, string $characters, int $offset = 0, ?int $length = null) : int
    {
        return $length === null ? \strspn($string, $characters, $offset) : \strspn($string, $characters, $offset, $length);
    }
    public static function substr_compare(string $haystack, string $needle, int $offset, ?int $length = null, bool $case_insensitive = \false) : int
    {
        return \substr_compare($haystack, $needle, $offset, $length ?? \max(\strlen($needle), \strlen($haystack) - ($offset < 0 ? \strlen($haystack) + $offset : $offset)), $case_insensitive);
    }
    public static function substr_count(string $haystack, string $needle, int $offset = 0, ?int $length = null) : int
    {
        return $length === null ? \substr_count($haystack, $needle, $offset) : \substr_count($haystack, $needle, $offset, $length);
    }
    public static function substr_replace(array|string $string, array|string $replace, array|int $offset, array|int|null $length = null) : string|array
    {
        return $length === null ? \substr_replace($string, $replace, $offset) : \substr_replace($string, $replace, $offset, $length);
    }
    public static function substr(string $string, int $offset, ?int $length = null) : string
    {
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
