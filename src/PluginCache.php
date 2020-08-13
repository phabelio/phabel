<?php

namespace Phabel;

use ReflectionMethod;

/**
 * Caches plugin information.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class PluginCache
{
    /**
     * Enter method names for each plugin.
     *
     * @var array<class-string<PluginInterface>, string[]>
     */
    private static array $enterMethods = [];
    /**
     * Leave method names.
     *
     * @var array<class-string<PluginInterface>, string[]>
     */
    private static array $leaveMethods = [];

    /**
     * Cache method information.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return void
     */
    private static function cacheMethods(string $plugin): void
    {
        if (!isset(self::$enterMethods[$plugin])) {
            self::$enterMethods[$plugin] = [];
            self::$leaveMethods[$plugin] = [];
            foreach (\get_class_methods($plugin) as $method) {
                if (\str_starts_with($method, 'enter')) {
                    $reflection = new ReflectionMethod($plugin, $method);
                    $type = $reflection->getParameters()[0]->getType()->getName();
                    self::$enterMethods[$plugin][$type] []= $method;
                } elseif (\str_starts_with($method, 'leave')) {
                    $reflection = new ReflectionMethod($plugin, $method);
                    $type = $reflection->getParameters()[0]->getType()->getName();
                    self::$leaveMethods[$plugin][$type] []= $method;
                }
            }
        }
    }
    /**
     * Return whether this plugin can be required by another plugin.
     *
     * If false, this plugin should only be extended by other plugins, to reduce complexity.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return boolean
     */
    public static function canBeRequired(string $plugin): bool
    {
        self::cacheMethods($plugin);
        return empty(self::$leaveMethods[$plugin]);
    }
    /**
     * Get enter methods array.
     *
     * @param class-string<PluginInterface> $plugin Plugin name
     *
     * @return array<string, string[]>
     */
    public static function enterMethods(string $plugin): array
    {
        self::cacheMethods($plugin);
        return self::$enterMethods[$plugin];
    }
    /**
     * Get leave methods array.
     *
     * @param class-string<PluginInterface> $plugin Plugin name
     *
     * @return array<string, string[]>
     */
    public static function leaveMethods(string $plugin): array
    {
        self::cacheMethods($plugin);
        return self::$leaveMethods[$plugin];
    }
    /**
     * Get runBefore requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return array<class-string<PluginInterface>, array>
     */
    public static function runBefore(string $plugin): array
    {
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$plugin])) {
            return $cache[$plugin];
        }
        return $cache[$plugin] = self::simplify($plugin::runBefore());
    }
    /**
     * Get runAfter requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return array<class-string<PluginInterface>, array>
     */
    public static function runAfter(string $plugin): array
    {
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$plugin])) {
            return $cache[$plugin];
        }
        return $cache[$plugin] = self::simplify($plugin::runAfter());
    }
    /**
     * Get runWithBefore requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return array<class-string<PluginInterface>, array>
     */
    public static function runWithBefore(string $plugin): array
    {
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$plugin])) {
            return $cache[$plugin];
        }
        return $cache[$plugin] = self::simplify($plugin::runWithBefore());
    }
    /**
     * Get runWithAfter requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return array<class-string<PluginInterface>, array>
     */
    public static function runWithAfter(string $plugin): array
    {
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$plugin])) {
            return $cache[$plugin];
        }
        return $cache[$plugin] = self::simplify($plugin::runWithAfter());
    }
    /**
     * Simplify requirements.
     *
     * @param (array<class-string<PluginInterface>, array>|class-string<PluginInterface>[]) $requirements Requirements
     *
     * @return array<class-string<PluginInterface>, array>
     */
    private static function simplify(array $requirements): array
    {
        return isset($requirements[0]) ? \array_fill_keys($requirements, []) : $requirements;
    }
}
