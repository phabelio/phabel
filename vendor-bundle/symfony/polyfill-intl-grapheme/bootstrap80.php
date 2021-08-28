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
use Phabel\Symfony\Polyfill\Intl\Grapheme as p;
if (!\defined('GRAPHEME_EXTR_COUNT')) {
    \define('GRAPHEME_EXTR_COUNT', 0);
}
if (!\defined('GRAPHEME_EXTR_MAXBYTES')) {
    \define('GRAPHEME_EXTR_MAXBYTES', 1);
}
if (!\defined('GRAPHEME_EXTR_MAXCHARS')) {
    \define('GRAPHEME_EXTR_MAXCHARS', 2);
}
if (!\function_exists('grapheme_extract')) {
    function grapheme_extract($haystack, $size, $type = \GRAPHEME_EXTR_COUNT, $offset = 0, &$next = null)
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
        if (!\is_null($size)) {
            if (!\is_int($size)) {
                if (!(\is_bool($size) || \is_numeric($size))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($size) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($size) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $size = (int) $size;
                }
            }
        }
        if (!\is_null($type)) {
            if (!\is_int($type)) {
                if (!(\is_bool($type) || \is_numeric($type))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($type) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $type = (int) $type;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_extract((string) $haystack, (int) $size, (int) $type, (int) $offset, $next);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_stripos')) {
    function grapheme_stripos($haystack, $needle, $offset = 0)
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
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_stripos((string) $haystack, (string) $needle, (int) $offset);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_stristr')) {
    function grapheme_stristr($haystack, $needle, $beforeNeedle = \false)
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
        if (!\is_null($beforeNeedle)) {
            if (!\is_bool($beforeNeedle)) {
                if (!(\is_bool($beforeNeedle) || \is_numeric($beforeNeedle) || \is_string($beforeNeedle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($beforeNeedle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($beforeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $beforeNeedle = (bool) $beforeNeedle;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_stristr((string) $haystack, (string) $needle, (bool) $beforeNeedle);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_strlen')) {
    function grapheme_strlen($string)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_strlen((string) $string);
        if (!($phabelReturn instanceof false || \is_null($phabelReturn))) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_strpos')) {
    function grapheme_strpos($haystack, $needle, $offset = 0)
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
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_strpos((string) $haystack, (string) $needle, (int) $offset);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_strripos')) {
    function grapheme_strripos($haystack, $needle, $offset = 0)
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
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_strripos((string) $haystack, (string) $needle, (int) $offset);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_strrpos')) {
    function grapheme_strrpos($haystack, $needle, $offset = 0)
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
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_strrpos((string) $haystack, (string) $needle, (int) $offset);
        if (!$phabelReturn instanceof false) {
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (int) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_strstr')) {
    function grapheme_strstr($haystack, $needle, $beforeNeedle = \false)
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
        if (!\is_null($beforeNeedle)) {
            if (!\is_bool($beforeNeedle)) {
                if (!(\is_bool($beforeNeedle) || \is_numeric($beforeNeedle) || \is_string($beforeNeedle))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($beforeNeedle) must be of type ?bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($beforeNeedle) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $beforeNeedle = (bool) $beforeNeedle;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_strstr((string) $haystack, (string) $needle, (bool) $beforeNeedle);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
if (!\function_exists('grapheme_substr')) {
    function grapheme_substr($string, $offset, $length = null)
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        if (!\is_null($offset)) {
            if (!\is_int($offset)) {
                if (!(\is_bool($offset) || \is_numeric($offset))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($offset) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($offset) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $offset = (int) $offset;
                }
            }
        }
        if (!\is_null($length)) {
            if (!\is_int($length)) {
                if (!(\is_bool($length) || \is_numeric($length))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($length) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($length) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $length = (int) $length;
                }
            }
        }
        $phabelReturn = p\Grapheme::grapheme_substr((string) $string, (int) $offset, $length);
        if (!$phabelReturn instanceof false) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}
