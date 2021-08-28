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
use Phabel\Symfony\Polyfill\Intl\Normalizer as p;
if (!\function_exists('normalizer_is_normalized')) {
    function normalizer_is_normalized($string, $form = p\Normalizer::FORM_C)
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
        if (!\is_null($form)) {
            if (!\is_int($form)) {
                if (!(\is_bool($form) || \is_numeric($form))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($form) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($form) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $form = (int) $form;
                }
            }
        }
        $phabelReturn = p\Normalizer::isNormalized((string) $string, (int) $form);
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
if (!\function_exists('normalizer_normalize')) {
    function normalizer_normalize($string, $form = p\Normalizer::FORM_C)
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
        if (!\is_null($form)) {
            if (!\is_int($form)) {
                if (!(\is_bool($form) || \is_numeric($form))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($form) must be of type ?int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($form) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $form = (int) $form;
                }
            }
        }
        $phabelReturn = p\Normalizer::normalize((string) $string, (int) $form);
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
