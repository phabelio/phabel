<?php

namespace Phabel\Target\Php72;

use __PHP_Incomplete_Class;
use Phabel\Plugin;
use Phabel\Target\Polyfill as TargetPolyfill;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    // Todo: class constants moved from DateTime to DateTimeInterface
    // Todo: misc Date class changes
    // Todo: password_hash: Support for Argon2i passwords using <constant>PASSWORD_ARGON2I</constant> was added
    // Todo: ReflectionClass::getMethods, ReflectionClass::getProperties nullable
    // Todo: substr_compare
    // Skip PREG_UNMATCHED_AS_NULL
    public static function json_decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0)
    {
        $associative ??= !!($flags & JSON_OBJECT_AS_ARRAY);
        return \json_decode($json, $associative, $depth, $flags);
    }
    public static function mb_convert_encoding(array|string $string, string $to_encoding, array|string|null $from_encoding = null): array|string|bool
    {
        $from_encoding ??= \mb_internal_encoding();
        if (\is_array($string)) {
            foreach ($string as $k => $s) {
                if (\is_array($from_encoding)) {
                    $string[$k] = self::mb_convert_encoding($s, $to_encoding, $from_encoding[$k] ?? null);
                } else {
                    $string[$k] = self::mb_convert_encoding($s, $to_encoding, $from_encoding);
                }
            }
            return $string;
        }
        return \mb_convert_encoding($string, $to_encoding, $from_encoding);
    }
    public static function number_format(float $num, int $decimals = 0, ?string $decimal_separator = ".", ?string $thousands_separator = ","): string
    {
        $res = \number_format($num, $decimals, $decimal_separator, $thousands_separator);
        return $res === '-0' ? '0' : $res;
    }
    public static function is_object($stuff): bool
    {
        return $stuff instanceof __PHP_Incomplete_Class ? true : \is_object($stuff);
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config): array
    {
        return [TargetPolyfill::class => [self::class => true]];
    }
}
