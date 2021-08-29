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
    function u($string = '') : UnicodeString
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        return new UnicodeString($string ?? '');
    }
}
if (!\function_exists(b::class)) {
    function b($string = '') : ByteString
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        return new ByteString($string ?? '');
    }
}
if (!\function_exists(s::class)) {
    /**
     * @return UnicodeString|ByteString
     */
    function s($string = '') : AbstractString
    {
        if (!\is_null($string)) {
            if (!\is_string($string)) {
                if (!(\is_string($string) || \Phabel\Target\Php72\Polyfill::is_object($string) && \method_exists($string, '__toString') || (\is_bool($string) || \is_numeric($string)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($string) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($string) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $string = (string) $string;
                }
            }
        }
        $string = $string ?? '';
        return \preg_match('//u', $string) ? new UnicodeString($string) : new ByteString($string);
    }
}
