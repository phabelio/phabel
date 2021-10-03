<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Symfony\Polyfill\Intl\Normalizer as p;
if (!function_exists('normalizer_is_normalized')) {
    function normalizer_is_normalized(?string $string, ?int $form = p\Normalizer::FORM_C) : bool
    {
        return p\Normalizer::isNormalized((string) $string, (int) $form);
    }
}
if (!function_exists('normalizer_normalize')) {
    function normalizer_normalize(?string $string, ?int $form = p\Normalizer::FORM_C)
    {
        $phabelReturn = p\Normalizer::normalize((string) $string, (int) $form);
        if (!($phabelReturn === false)) {
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \Phabel\Target\Php72\Polyfill::is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type false|string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
        }
        return $phabelReturn;
    }
}