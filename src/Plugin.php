<?php

namespace Phabel;

use Phabel\PluginGraph\PackageContext;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;

/**
 * Plugin.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
abstract class Plugin extends Tools implements PluginInterface
{
    /**
     * Configuration array.
     */
    private array $config = [];
    /**
     * Package context.
     */
    private PackageContext $ctx;
    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
        $this->config = $config;
    }
    /**
     * Set package context.
     *
     * @param PackageContext $ctx Ctx
     *
     * @return void
     */
    public function setPackageContext(PackageContext $ctx): void
    {
        $this->ctx = $ctx;
    }
    /**
     * Get package context.
     *
     * @return PackageContext
     */
    public function getPackageContext(): PackageContext
    {
        return $this->ctx;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun(string $package): bool
    {
        return $this->ctx->has($package);
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool
    {
        return true;
    }
    /**
     * Call polyfill function from current plugin.
     *
     * @param string   $name          Function name
     * @param Expr|Arg ...$parameters Parameters
     *
     * @return StaticCall
     */
    protected static function callPoly(string $name, ...$parameters): StaticCall
    {
        return self::call([static::class, $name], ...$parameters);
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig(string $key, $default)
    {
        return $this->config[$key] ?? $default;
    }
    /**
     * {@inheritDoc}
     */
    public function setConfig(string $key, $value): void
    {
        $this->config[$key] = $value;
    }
    /**
     * {@inheritDoc}
     */
    public static function mergeConfigs(array ...$configs): array
    {
        return $configs;
    }
    /**
     * {@inheritDoc}
     */
    public static function splitConfig(array $config): array
    {
        return [$config];
    }
    /**
     * {@inheritDoc}
     */
    public function getComposerRequires(): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runAfter(array $config): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runBefore(array $config): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runWithBefore(array $config): array
    {
        return [];
    }
    /**
     * {@inheritDoc}
     */
    public static function runWithAfter(array $config): array
    {
        return [];
    }
}
