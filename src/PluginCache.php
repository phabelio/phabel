<?php

namespace Phabel;

use ReflectionMethod;

/**
 * Caches plugin information.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class PluginCache
{
    /**
     * Enter method names for each plugin.
     *
     * @var array<class-string<PluginInterface>, string[]>
     */
    private static $enterMethods = [];
    /**
     * Leave method names.
     *
     * @var array<class-string<PluginInterface>, string[]>
     */
    private static $leaveMethods = [];
    /**
     * Cache method information.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     *
     * @return void
     */
    private static function cacheMethods($plugin)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!isset(self::$enterMethods[$plugin])) {
            self::$enterMethods[$plugin] = [];
            self::$leaveMethods[$plugin] = [];
            foreach (\get_class_methods($plugin) as $method) {
                if (\str_starts_with($method, 'enter')) {
                    $reflection = new ReflectionMethod($plugin, $method);
                    $type = $reflection->getParameters()[0]->getType()->getName();
                    self::$enterMethods[$plugin][$type][] = $method;
                } elseif (\str_starts_with($method, 'leave')) {
                    $reflection = new ReflectionMethod($plugin, $method);
                    $type = $reflection->getParameters()[0]->getType()->getName();
                    self::$leaveMethods[$plugin][$type][] = $method;
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
    public static function canBeRequired($plugin)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        self::cacheMethods($plugin);
        $phabelReturn = empty(self::$leaveMethods[$plugin]);
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get enter methods array.
     *
     * @param class-string<PluginInterface> $plugin Plugin name
     *
     * @return array<string, string[]>
     */
    public static function enterMethods($plugin)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        self::cacheMethods($plugin);
        $phabelReturn = self::$enterMethods[$plugin];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get leave methods array.
     *
     * @param class-string<PluginInterface> $plugin Plugin name
     *
     * @return array<string, string[]>
     */
    public static function leaveMethods($plugin)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        self::cacheMethods($plugin);
        $phabelReturn = self::$leaveMethods[$plugin];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get previous requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     * @param array                         $config Config
     *
     * @return array<string, array>
     * @psalm-return array<class-string<PluginInterface>, array>
     */
    public static function previous($plugin, array $config)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $pluginConfig = $plugin . \var_export($config, true);
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$pluginConfig])) {
            $phabelReturn = $cache[$pluginConfig];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = $cache[$pluginConfig] = self::simplify($plugin::previous($config));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get next requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     * @param array                         $config Config
     *
     * @return array<string, array>
     * @psalm-return array<class-string<PluginInterface>, array>
     */
    public static function next($plugin, array $config)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $pluginConfig = $plugin . \var_export($config, true);
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$pluginConfig])) {
            $phabelReturn = $cache[$pluginConfig];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = $cache[$pluginConfig] = self::simplify($plugin::next($config));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get withPrevious requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     * @param array                         $config Config
     *
     * @return array<string, array>
     * @psalm-return array<class-string<PluginInterface>, array>
     */
    public static function withPrevious($plugin, array $config)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $pluginConfig = $plugin . \var_export($config, true);
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$pluginConfig])) {
            $phabelReturn = $cache[$pluginConfig];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = $cache[$pluginConfig] = self::simplify($plugin::withPrevious($config));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get withNext requirements.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     * @param array                         $config Config
     *
     * @return array<string, array>
     * @psalm-return array<class-string<PluginInterface>, array>
     */
    public static function withNext($plugin, array $config)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $pluginConfig = $plugin . \var_export($config, true);
        /** @var array<class-string<PluginInterface>, array<class-string<PluginInterface>, array>> */
        static $cache = [];
        if (isset($cache[$pluginConfig])) {
            $phabelReturn = $cache[$pluginConfig];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = $cache[$pluginConfig] = self::simplify($plugin::withNext($config));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Simplify requirements.
     *
     * @param (array<class-string<PluginInterface>, array>|class-string<PluginInterface>[]) $requirements Requirements
     *
     * @return array<string, array>
     * @psalm-return array<class-string<PluginInterface>, array>
     */
    private static function simplify(array $requirements)
    {
        $phabelReturn = isset($requirements[0]) ? \array_fill_keys($requirements, []) : $requirements;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
