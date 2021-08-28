<?php

namespace Phabel\Target\Php70;

use AssertionError;
use IntlChar;
use Phabel\Plugin;
use Phabel\Target\Php;
use Phabel\Target\Polyfill as TargetPolyfill;
use Phabel\Tools;
use ValueError;
\define('BIG_ENDIAN', \pack('L', 1) === \pack('N', 1));
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    const IS_WINDOWS = \PHP_OS_FAMILY === 'Windows';
    const CONSTANTS = [IntlChar::class => ['NO_NUMERIC_VALUE' => -123456789.0]];
    // Todo: dns_get_record CAA
    // Todo: filters
    // Todo: getenv/putenv
    // Todo: unpack
    /**
     * Get composer requirements.
     *
     * @return array
     */
    public static function getComposerRequires(array $config)
    {
        $phabelReturn = ['symfony/polyfill-php72' => Php::POLYFILL_VERSIONS['symfony/polyfill-php72']];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function assert($assertion, $exception = null)
    {
        if (!($exception instanceof \Exception || $exception instanceof \Error || \is_null($exception) || \is_null($exception))) {
            if (!\is_string($exception)) {
                if (!(\is_string($exception) || \is_object($exception) && \method_exists($exception, '__toString') || (\is_bool($exception) || \is_numeric($exception)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($exception) must be of type ?Throwable|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($exception) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $exception = (string) $exception;
            }
        }
        if ($assertion || Tools::ini_get('zend.assertions') !== 1) {
            $phabelReturn = \true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $exception = new AssertionError('assert(false)');
        if (\is_null($exception)) {
            $exception = new AssertionError('assert(false)');
        } elseif (\is_string($exception)) {
            $exception = new AssertionError($exception);
        }
        if (Tools::ini_get('assert.exception')) {
            throw $exception;
        }
        \trigger_error("Uncaught {$exception}");
        $phabelReturn = \true;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function dirname($path, $levels = 1)
    {
        if (!\is_string($path)) {
            if (!(\is_string($path) || \is_object($path) && \method_exists($path, '__toString') || (\is_bool($path) || \is_numeric($path)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($path) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($path) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $path = (string) $path;
        }
        if (!\is_int($levels)) {
            if (!(\is_bool($levels) || \is_numeric($levels))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($levels) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($levels) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $levels = (int) $levels;
        }
        if ($levels === 1) {
            $phabelReturn = \dirname($path);
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($levels < 1) {
            throw new ValueError('dirname(): Argument #2 ($levels) must be greater than or equal to 1');
        }
        for ($x = \strlen($path) - 1; $x >= 0 && $levels > 0; $x--) {
            if ($path[$x] === '/' || self::IS_WINDOWS && $path[$x] === '\\') {
                --$levels;
            }
        }
        $path = \substr($path, \max(0, $x));
        $phabelReturn = $path === '' ? '.' : $path;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function get_defined_functions($exclude_disabled = \true)
    {
        if (!\is_bool($exclude_disabled)) {
            if (!(\is_bool($exclude_disabled) || \is_numeric($exclude_disabled) || \is_string($exclude_disabled))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($exclude_disabled) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($exclude_disabled) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $exclude_disabled = (bool) $exclude_disabled;
        }
        if ($exclude_disabled) {
            $disabled = \explode(',', Tools::ini_get('disable_functions') ?: '');
            $res = \get_defined_functions();
            $res['internal'] = \array_diff($res['internal'], $disabled);
            $phabelReturn = $res;
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = \get_defined_functions();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public static function substr($string, $offset, $length = null)
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $string = (string) $string;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $length = (int) $length;
            }
        }
        if (\strlen($string) === $offset) {
            $phabelReturn = '';
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        }
        $phabelReturn = \substr($string, $offset, $length);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    public static function iconv_substr($string, $offset, $length = null, $encoding = null)
    {
        if (!\is_string($string)) {
            if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $string = (string) $string;
        }
        if (!\is_int($offset)) {
            if (!(\is_bool($offset) || \is_numeric($offset))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $offset = (int) $offset;
        }
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
                if (!(\is_string($encoding) || \is_object($encoding) && \method_exists($encoding, '__toString') || (\is_bool($encoding) || \is_numeric($encoding)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($encoding) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($encoding) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $encoding = (string) $encoding;
            }
        }
        $encoding = isset($encoding) ? $encoding : \iconv_get_encoding('internal_encoding');
        $len = \iconv_strlen($string, $encoding);
        if ($len === $offset) {
            $phabelReturn = '';
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
        $length = isset($length) ? $length : $len;
        $phabelReturn = \iconv_substr($string, $offset, $length, $encoding);
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
    public static function pack($format, ...$values)
    {
        if (!\is_string($format)) {
            if (!(\is_string($format) || \is_object($format) && \method_exists($format, '__toString') || (\is_bool($format) || \is_numeric($format)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($format) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($format) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $format = (string) $format;
        }
        $l = \strlen($format);
        $y = 0;
        $newFormat = '';
        for ($x = 0; $x < $l; $x++) {
            $cur = $format[$x++];
            $repeater = '';
            for (; $x < $l && ($format[$x] === '*' || \is_numeric($format[$x])); $x++) {
                $repeater .= $format[$x];
            }
            $x--;
            $repeaterOrig = $repeater;
            if ($cur === 'a' || $cur === 'A' || $cur === 'h' || $cur === 'H' || $cur === '@') {
                $repeater = 1;
            } else {
                $repeater = $repeater === '*' ? \count($values) - $y : (int) $repeater;
            }
            if (BIG_ENDIAN && $cur === 'g' || !BIG_ENDIAN && $cur === 'G') {
                $cur = '';
                for ($z = $y; $z < $y + $repeater; $z++) {
                    $cur .= 'a4';
                    $values[$z] = \strrev(\pack('f', $values[$z]));
                }
                $newFormat .= $cur;
            } elseif (BIG_ENDIAN && $cur === 'e' || !BIG_ENDIAN && $cur === 'E') {
                $cur = '';
                for ($z = $y; $z < $y + $repeater; $z++) {
                    $cur .= 'a8';
                    $values[$z] = \strrev(\pack('d', $values[$z]));
                }
                $newFormat .= $cur;
            } else {
                $newFormat .= $cur . $repeaterOrig;
            }
        }
        $phabelReturn = \pack($newFormat, ...$values);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
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
