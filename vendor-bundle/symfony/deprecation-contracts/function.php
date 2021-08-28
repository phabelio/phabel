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
if (!\function_exists('Phabel\\trigger_deprecation')) {
    /**
     * Triggers a silenced deprecation notice.
     *
     * @param string $package The name of the Composer package that is triggering the deprecation
     * @param string $version The version of the package that introduced the deprecation
     * @param string $message The message of the deprecation
     * @param mixed  ...$args Values to insert in the message using printf() formatting
     *
     * @author Nicolas Grekas <p@tchwork.com>
     */
    function trigger_deprecation($package, $version, $message, ...$args)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $package = (string) $package;
            }
        }
        if (!\is_string($version)) {
            if (!(\is_string($version) || \is_object($version) && \method_exists($version, '__toString') || (\is_bool($version) || \is_numeric($version)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($version) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($version) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $version = (string) $version;
            }
        }
        if (!\is_string($message)) {
            if (!(\is_string($message) || \is_object($message) && \method_exists($message, '__toString') || (\is_bool($message) || \is_numeric($message)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($message) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($message) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $message = (string) $message;
            }
        }
        @\trigger_error(($package || $version ? "Since {$package} {$version}: " : '') . ($args ? \vsprintf($message, $args) : $message), \E_USER_DEPRECATED);
    }
}
