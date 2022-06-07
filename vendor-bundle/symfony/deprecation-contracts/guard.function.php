<?php

namespace PhabelVendor;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if (!\function_exists('PhabelVendor\\trigger_deprecation')) {
    /**
     * Triggers a silenced deprecation notice.
     *
     * @param string $package The name of the Composer package that is triggering the deprecation
     * @param string $version The version of the package that introduced the deprecation
     * @param string $message The message of the deprecation
     * @param mixed ...$args Values to insert in the message using printf() formatting
     *
     * @author Nicolas Grekas <p@tchwork.com>
     */
    function trigger_deprecation(string $package, string $version, string $message, ...$args) : void
    {
        foreach ($args as $phabelVariadicIndex => $phabelVariadic) {
            if (!\true) {
                throw new \TypeError(__METHOD__ . '(): Argument #' . (4 + $phabelVariadicIndex) . ' must be of type mixed, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($args) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
        }
        @\trigger_error(($package || $version ? "Since {$package} {$version}: " : '') . ($args ? \vsprintf($message, $args) : $message), \E_USER_DEPRECATED);
    }
}
