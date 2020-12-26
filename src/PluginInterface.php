<?php

namespace Phabel;

use Phabel\PluginGraph\PackageContext;

/**
 * Plugin interface.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
interface PluginInterface
{
    /**
     * Specify which plugins does this plugin require.
     *
     * At each level, the traverser will execute the enter and leave methods of the specified plugins, completing a full AST traversal before starting a new AST traversal for the current plugin.
     * Of course, this increases complexity as it forces an additional traversal of the AST.
     *
     * When possible, use the extends method to reduce complexity.
     *
     * @return array Plugin name(s)
     *
     * @psalm-return class-string<PluginInterface>[]|array<class-string<PluginInterface>, array>
     */
    public static function runAfter(array $config): array;
    /**
     * Specify which plugins should run before this plugin.
     *
     * @return array Plugin name(s)
     *
     * @psalm-return class-string<PluginInterface>[]|array<class-string<PluginInterface>, array>
     */
    public static function runBefore(array $config): array;
    /**
     * Specify which plugins does this plugin extend.
     *
     * At each depth level, the traverser will first execute the enter|leave methods of the specified plugins, then immediately execute the enter|leave methods of the current plugin.
     *
     * This is preferred, and allows only a single traversal of the AST.
     *
     * @return array Plugin name(s)
     *
     * @psalm-return class-string<PluginInterface>[]|array<class-string<PluginInterface>, array>
     */
    public static function runWithBefore(array $config): array;
    /**
     *
     * @return array Plugin name(s)
     *
     * @psalm-return class-string<PluginInterface>[]|array<class-string<PluginInterface>, array>
     */
    public static function runWithAfter(array $config): array;

    /**
     * Specify a list of composer dependencies.
     *
     * @return array
     */
    public function getComposerRequires(): array;
    /**
     * Set configuration array.
     *
     * @param array $config
     *
     * @return void
     */
    public function setConfigArray(array $config): void;
    /**
     * Set package context.
     *
     * @param PackageContext $ctx Ctx
     *
     * @return void
     */
    public function setPackageContext(PackageContext $ctx): void;
    /**
     * Get package context.
     *
     * @return PackageContext
     */
    public function getPackageContext(): PackageContext;
    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun(string $package): bool;
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool;
    /**
     * Get configuration key.
     *
     * @param string $key     Key
     * @param mixed  $default Default value, if key is not present
     *
     * @return mixed
     */
    public function getConfig(string $key, $default);

    /**
     * Set configuration key.
     *
     * @param string $key   Key
     * @param mixed  $value Value
     *
     * @return void
     */
    public function setConfig(string $key, $value): void;

    /**
     * Merge multiple configurations into one (or more).
     *
     * @param array ...$configs Configurations
     *
     * @return array[]
     */
    public static function mergeConfigs(array ...$configs): array;
    /**
     * Split configuration.
     *
     * For example, if you have a configuration that enables feature A, B and C, return three configuration arrays each enabling ONLY A, only B and only C.
     * This is used for optimizing the AST traversing process during resolution of the plugin graph.
     *
     * @param array $config Configuration
     *
     * @return array[]
     */
    public static function splitConfig(array $config): array;
}
