<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Component\String;

if (!\function_exists(u::class)) {
    function u($string = '')
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
        $phabelReturn = new UnicodeString(isset($string) ? $string : '');
        if (!$phabelReturn instanceof UnicodeString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type UnicodeString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (!\function_exists(b::class)) {
    function b($string = '')
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
        $phabelReturn = new ByteString(isset($string) ? $string : '');
        if (!$phabelReturn instanceof ByteString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ByteString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
if (!\function_exists(s::class)) {
    /**
     * @return UnicodeString|ByteString
     */
    function s($string = '')
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
        $string = isset($string) ? $string : '';
        $phabelReturn = \preg_match('//u', $string) ? new UnicodeString($string) : new ByteString($string);
        if (!$phabelReturn instanceof AbstractString) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type AbstractString, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
