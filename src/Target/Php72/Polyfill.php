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
    public static function json_decode(string $json, $associative = null, int $depth = 512, int $flags = 0)
    {
        if (!\is_null($associative)) {
            if (!\is_bool($associative)) {
                if (!(\is_bool($associative) || \is_numeric($associative) || \is_string($associative))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($associative) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($associative) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $associative = (bool) $associative;
            }
        }
        $associative = $associative ?? !!($flags & \JSON_OBJECT_AS_ARRAY);
        return \json_decode($json, $associative, $depth, $flags);
    }
    public static function mb_convert_encoding($string, string $to_encoding, $from_encoding = null)
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                if (!\is_array($string)) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type array|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
            } else {
                $string = (string) $string;
            }
        }
        if (!(\is_null($from_encoding) || \is_null($from_encoding))) {
            if (!\is_string($from_encoding)) {
                if (!(\is_string($from_encoding) || \is_object($from_encoding) && \method_exists($from_encoding, '__toString') || (\is_bool($from_encoding) || \is_numeric($from_encoding)))) {
                    if (!\is_array($from_encoding)) {
                        throw new \TypeError(__METHOD__ . '(): Argument #3 ($from_encoding) must be of type ?array|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($from_encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                } else {
                    $from_encoding = (string) $from_encoding;
                }
            }
        }
        $from_encoding = $from_encoding ?? \mb_internal_encoding();
        if (\is_array($string)) {
            foreach ($string as $k => $s) {
                if (\is_array($from_encoding)) {
                    $string[$k] = self::mb_convert_encoding($s, $to_encoding, $from_encoding[$k] ?? null);
                } else {
                    $string[$k] = self::mb_convert_encoding($s, $to_encoding, $from_encoding);
                }
            }
            $phabelReturn = $string;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    if (!\is_string($phabelReturn)) {
                        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                            if (!\is_array($phabelReturn)) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type array|string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                        } else {
                            $phabelReturn = (string) $phabelReturn;
                        }
                    }
                } else {
                    $phabelReturn = (bool) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = \mb_convert_encoding($string, $to_encoding, $from_encoding);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        if (!\is_array($phabelReturn)) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type array|string|bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function number_format(float $num, int $decimals = 0, $decimal_separator = ".", $thousands_separator = ",") : string
    {
        if (!\is_null($decimal_separator)) {
            if (!\is_string($decimal_separator)) {
                if (!(\is_string($decimal_separator) || \is_object($decimal_separator) && \method_exists($decimal_separator, '__toString') || (\is_bool($decimal_separator) || \is_numeric($decimal_separator)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($decimal_separator) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($decimal_separator) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $decimal_separator = (string) $decimal_separator;
            }
        }
        if (!\is_null($thousands_separator)) {
            if (!\is_string($thousands_separator)) {
                if (!(\is_string($thousands_separator) || \is_object($thousands_separator) && \method_exists($thousands_separator, '__toString') || (\is_bool($thousands_separator) || \is_numeric($thousands_separator)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($thousands_separator) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($thousands_separator) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $thousands_separator = (string) $thousands_separator;
            }
        }
        $res = \number_format($num, $decimals, $decimal_separator, $thousands_separator);
        return $res === '-0' ? '0' : $res;
    }
    public static function is_object($stuff) : bool
    {
        return $stuff instanceof __PHP_Incomplete_Class ? \true : \is_object($stuff);
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config) : array
    {
        return [TargetPolyfill::class => [self::class => \true]];
    }
}
