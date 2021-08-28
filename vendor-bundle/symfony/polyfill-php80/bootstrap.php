<?php

namespace Phabel;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Phabel\Symfony\Polyfill\Php80 as p;
if (\PHP_VERSION_ID >= 80000) {
    return;
}
if (!\defined('FILTER_VALIDATE_BOOL') && \defined('FILTER_VALIDATE_BOOLEAN')) {
    \define('FILTER_VALIDATE_BOOL', \FILTER_VALIDATE_BOOLEAN);
}
if (!\function_exists('fdiv')) {
    function fdiv($num1, $num2)
    {
        if (!\is_float($num1)) {
            if (!(\is_bool($num1) || \is_numeric($num1))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($num1) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($num1) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $num1 = (double) $num1;
            }
        }
        if (!\is_float($num2)) {
            if (!(\is_bool($num2) || \is_numeric($num2))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($num2) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($num2) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $num2 = (double) $num2;
            }
        }
        $phabelReturn = p\Php80::fdiv($num1, $num2);
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('preg_last_error_msg')) {
    function preg_last_error_msg()
    {
        $phabelReturn = p\Php80::preg_last_error_msg();
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        $phabelReturn = p\Php80::str_contains(isset($haystack) ? $haystack : '', isset($needle) ? $needle : '');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        $phabelReturn = p\Php80::str_starts_with(isset($haystack) ? $haystack : '', isset($needle) ? $needle : '');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle)
    {
        if (!\is_null($haystack)) {
            if (!\is_string($haystack)) {
                if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $haystack = (string) $haystack;
                }
            }
        }
        if (!\is_null($needle)) {
            if (!\is_string($needle)) {
                if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $needle = (string) $needle;
                }
            }
        }
        $phabelReturn = p\Php80::str_ends_with(isset($haystack) ? $haystack : '', isset($needle) ? $needle : '');
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('get_debug_type')) {
    function get_debug_type($value)
    {
        $phabelReturn = p\Php80::get_debug_type($value);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('get_resource_id')) {
    function get_resource_id($resource)
    {
        $phabelReturn = p\Php80::get_resource_id($resource);
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
