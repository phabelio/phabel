<?php

namespace Phabel\Target\Php70;

use IntlChar;
use Phabel\Plugin;
use Phabel\Target\Php;
use ValueError;

\define('BIG_ENDIAN', \pack('L', 1) === \pack('N', 1));

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Polyfill extends Plugin
{
    private const IS_WINDOWS = PHP_OS_FAMILY === 'Windows';
    public const CONSTANTS = [
        IntlChar::class => [
            'NO_NUMERIC_VALUE' => -123456789.0
        ]
    ];

    // Todo: dns_get_record CAA
    // Todo: filters

    /**
     * Get composer requirements.
     *
     * @return array
     */
    public static function getComposerRequires(array $config): array
    {
        return [
            'symfony/polyfill-php72' => Php::POLYFILL_VERSIONS['symfony/polyfill-php72']
        ];
    }

    /**
     * Environment variables modified with putenv.
     *
     * @var array
     */
    private static array $modifiedVars = [];

    public static function putenv(string $assignment): bool
    {
        [$key, $var] = \explode("=", $assignment, 2);
        self::$modifiedVars[$key] = $var;
        return \putenv($assignment);
    }
    public static function getenv(string $varname, bool $local_only = false): string|bool
    {
        if ($local_only) {
            if (\array_key_exists($varname, self::$modifiedVars)) {
                return self::$modifiedVars[$varname];
            }
            if (\array_key_exists($varname, $_ENV)) {
                return $_ENV[$varname];
            }
            return false;
        }
        return \getenv($varname);
    }

    public static function assert($assertion, $exception): bool
    {
    }


    public static function dirname(string $path, int $levels = 1): string
    {
        if ($levels === 1) {
            return \dirname($path);
        }
        if ($levels < 1) {
            throw new ValueError('dirname(): Argument #2 ($levels) must be greater than or equal to 1');
        }
        for ($x = \strlen($path)-1; $x >= 0 && $levels > 0; $x--) {
            if ($path[$x] === '/' || (self::IS_WINDOWS && $path[$x] === '\\')) {
                --$levels;
            }
        }
        $path = \substr($path, \max(0, $x));
        return $path === '' ? '.' : $path;
    }

    public static function get_defined_functions(bool $exclude_disabled = false): array
    {
        if ($exclude_disabled) {
            $disabled = [];
            try {
                if (\function_exists('ini_get')) {
                    $disabled = \explode(",", @\ini_get('disable_functions'));
                }
            } catch (\Throwable $e) {
            }
            $res = \get_defined_functions();
            $res['internal'] = \array_diff($res['internal'], $disabled);
            return $res;
        }
        return \get_defined_functions();
    }

    public static function substr(string $string, int $offset, ?int $length = null): string
    {
        if (\strlen($string) === $offset) {
            return '';
        }
        return \substr($string, $offset, $length);
    }

    public static function iconv_substr(string $string, int $offset, ?int $length = null, ?string $encoding = null): string|bool
    {
        $encoding ??= \iconv_get_encoding('internal_encoding');
        $len = \iconv_strlen($string, $encoding);
        if ($len === $offset) {
            return '';
        }
        $length ??= $len;
        return \iconv_substr($string, $offset, $length, $encoding);
    }

    public static function pack(string $format, ...$values): string
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
                $repeater = $repeater === '*' ? \count($values)-$y : (int) $repeater;
            }

            if ((BIG_ENDIAN && $cur === 'g') || (!BIG_ENDIAN && $cur === 'G')) {
                $cur = '';
                for ($z = $y; $z < $y+$repeater; $z++) {
                    $cur .= 'a4';
                    $values[$z] = \strrev(\pack('f', $values[$z]));
                }
                $newFormat .= $cur;
            } elseif ((BIG_ENDIAN && $cur === 'e') || (!BIG_ENDIAN && $cur === 'E')) {
                $cur = '';
                for ($z = $y; $z < $y+$repeater; $z++) {
                    $cur .= 'a8';
                    $values[$z] = \strrev(\pack('d', $values[$z]));
                }
                $newFormat .= $cur;
            } else {
                $newFormat .= $cur.$repeaterOrig;
            }
        }

        return \pack($newFormat, ...$values);
    }
}
