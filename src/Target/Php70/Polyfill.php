<?php

namespace Phabel\Target\Php70;

use AssertionError;
use IntlChar;
use Phabel\Plugin;
use Phabel\Target\Php;
use Phabel\Target\Polyfill as TargetPolyfill;
use Phabel\Tools;
use PhabelVendor\PhpParser\Node;
use Throwable;
use ValueError;
\define('BIG_ENDIAN', \pack('L', 1) === \pack('N', 1));
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    private const IS_WINDOWS = \PHP_OS_FAMILY === 'Windows';
    public const CONSTANTS = [IntlChar::class => ['NO_NUMERIC_VALUE' => -123456789.0]];
    // Todo: dns_get_record CAA
    // Todo: filters
    // Todo: getenv/putenv
    // Todo: unpack
    /**
     * Get composer requirements.
     *
     * @return array
     */
    public static function getComposerRequires(array $config) : array
    {
        if (\str_starts_with(Node::class, 'Phabel')) {
            return [];
        }
        return ['symfony/polyfill-php72' => Php::POLYFILL_VERSIONS['symfony/polyfill-php72']];
    }
    /**
     * @param (string | Throwable | null) $exception
     */
    public static function assert($assertion, $exception = null) : bool
    {
        if (!(\is_string($exception) || $exception instanceof Throwable || \is_null($exception) || \is_null($exception))) {
            if (!(\is_string($exception) || \is_object($exception) && \method_exists($exception, '__toString') || (\is_bool($exception) || \is_numeric($exception)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($exception) must be of type ?Throwable|string|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($exception) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $exception = (string) $exception;
        }
        if ($assertion || Tools::ini_get('zend.assertions') !== 1) {
            return \true;
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
        return \true;
    }
    /**
     *
     */
    public static function dirname(string $path, int $levels = 1) : string
    {
        if ($levels === 1) {
            return \dirname($path);
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
        return $path === '' ? '.' : $path;
    }
    /**
     *
     */
    public static function get_defined_functions(bool $exclude_disabled = \true) : array
    {
        if ($exclude_disabled) {
            $disabled = \explode(',', Tools::ini_get('disable_functions') ?: '');
            $res = \get_defined_functions();
            $res['internal'] = \array_diff($res['internal'], $disabled);
            return $res;
        }
        return \get_defined_functions();
    }
    /**
     *
     */
    public static function substr(string $string, int $offset, ?int $length = null) : string
    {
        if (\strlen($string) === $offset) {
            return '';
        }
        return \substr($string, $offset, $length);
    }
    /**
     * @return (bool | string)
     */
    public static function iconv_substr(string $string, int $offset, ?int $length = null, ?string $encoding = null)
    {
        $encoding = $encoding ?? \iconv_get_encoding('internal_encoding');
        $len = \iconv_strlen($string, $encoding);
        if ($len === $offset) {
            $phabelReturn = '';
            if (!(\is_bool($phabelReturn) || \is_string($phabelReturn))) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $length = $length ?? $len;
        $phabelReturn = \iconv_substr($string, $offset, $length, $encoding);
        if (!(\is_bool($phabelReturn) || \is_string($phabelReturn))) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /**
     *
     */
    public static function pack(string $format, ...$values) : string
    {
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
        return \pack($newFormat, ...$values);
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config) : array
    {
        return [TargetPolyfill::class => [self::class => \true]];
    }
}
