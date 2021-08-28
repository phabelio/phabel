<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Polyfill\Php80;

/**
 * @author Ion Bazan <ion.bazan@gmail.com>
 * @author Nico Oelgart <nicoswd@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
final class Php80
{
    public static function fdiv($dividend, $divisor)
    {
        if (!\is_float($dividend)) {
            if (!(\is_bool($dividend) || \is_numeric($dividend))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($dividend) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($dividend) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $dividend = (double) $dividend;
            }
        }
        if (!\is_float($divisor)) {
            if (!(\is_bool($divisor) || \is_numeric($divisor))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($divisor) must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($divisor) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $divisor = (double) $divisor;
            }
        }
        $phabelReturn = @($dividend / $divisor);
        if (!\is_float($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type float, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (double) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function get_debug_type($value)
    {
        switch (\true) {
            case null === $value:
                $phabelReturn = 'null';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_bool($value):
                $phabelReturn = 'bool';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_string($value):
                $phabelReturn = 'string';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_array($value):
                $phabelReturn = 'array';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_int($value):
                $phabelReturn = 'int';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_float($value):
                $phabelReturn = 'float';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \is_object($value):
                break;
            case $value instanceof \__PHP_Incomplete_Class:
                $phabelReturn = '__PHP_Incomplete_Class';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            default:
                if (null === ($type = @\get_resource_type($value))) {
                    $phabelReturn = 'unknown';
                    if (!\is_string($phabelReturn)) {
                        if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        } else {
                            $phabelReturn = (string) $phabelReturn;
                        }
                    }
                    return $phabelReturn;
                }
                if ('Unknown' === $type) {
                    $type = 'closed';
                }
                $phabelReturn = "resource ({$type})";
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
        }
        $class = \get_class($value);
        if (\false === \strpos($class, '@')) {
            $phabelReturn = $class;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $phabelReturn = ((\get_parent_class($class) ?: \key(\class_implements($class))) ?: 'class') . '@anonymous';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function get_resource_id($res)
    {
        if (!\is_resource($res) && null === @\get_resource_type($res)) {
            throw new \TypeError(\sprintf('Argument 1 passed to get_resource_id() must be of the type resource, %s given', \get_debug_type($res)));
        }
        $phabelReturn = (int) $res;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (int) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function preg_last_error_msg()
    {
        switch (\preg_last_error()) {
            case \PREG_INTERNAL_ERROR:
                $phabelReturn = 'Internal error';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_BAD_UTF8_ERROR:
                $phabelReturn = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_BAD_UTF8_OFFSET_ERROR:
                $phabelReturn = 'The offset did not correspond to the beginning of a valid UTF-8 code point';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_BACKTRACK_LIMIT_ERROR:
                $phabelReturn = 'Backtrack limit exhausted';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_RECURSION_LIMIT_ERROR:
                $phabelReturn = 'Recursion limit exhausted';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_JIT_STACKLIMIT_ERROR:
                $phabelReturn = 'JIT stack limit exhausted';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            case \PREG_NO_ERROR:
                $phabelReturn = 'No error';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
            default:
                $phabelReturn = 'Unknown error';
                if (!\is_string($phabelReturn)) {
                    if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (string) $phabelReturn;
                    }
                }
                return $phabelReturn;
        }
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
    public static function str_contains($haystack, $needle)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $haystack = (string) $haystack;
            }
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $phabelReturn = '' === $needle || \false !== \strpos($haystack, $needle);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function str_starts_with($haystack, $needle)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $haystack = (string) $haystack;
            }
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $phabelReturn = 0 === \strncmp($haystack, $needle, \strlen($needle));
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public static function str_ends_with($haystack, $needle)
    {
        if (!\is_string($haystack)) {
            if (!(\is_string($haystack) || \is_object($haystack) && \method_exists($haystack, '__toString') || (\is_bool($haystack) || \is_numeric($haystack)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($haystack) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($haystack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $haystack = (string) $haystack;
            }
        }
        if (!\is_string($needle)) {
            if (!(\is_string($needle) || \is_object($needle) && \method_exists($needle, '__toString') || (\is_bool($needle) || \is_numeric($needle)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($needle) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($needle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $needle = (string) $needle;
            }
        }
        $phabelReturn = '' === $needle || '' !== $haystack && 0 === \substr_compare($haystack, $needle, -\strlen($needle));
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
