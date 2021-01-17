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
    private $config = [];
    /**
     * Package context.
     */
    private $ctx;
    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config)
    {
        $this->config = $config;
    }
    /**
     * Get configuration array.
     *
     * @return array
     */
    public function getConfigArray()
    {
        $phabelReturn = $this->config;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set package context.
     *
     * @param PackageContext $ctx Ctx
     *
     * @return void
     */
    public function setPackageContext(PackageContext $ctx)
    {
        $this->ctx = $ctx;
    }
    /**
     * Get package context.
     *
     * @return PackageContext
     */
    public function getPackageContext()
    {
        $phabelReturn = $this->ctx;
        if (!$phabelReturn instanceof PackageContext) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type PackageContext, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun($package)
    {
        if (!\is_string($package)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = $this->ctx->has($package);
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile($file)
    {
        if (!\is_string($file)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = true;
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Call polyfill function from current plugin.
     *
     * @param string   $name          Function name
     * @param Expr|Arg ...$parameters Parameters
     *
     * @return StaticCall
     */
    protected static function callPoly($name, ...$parameters)
    {
        if (!\is_string($name)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = self::call([static::class, $name], ...$parameters);
        if (!$phabelReturn instanceof StaticCall) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type StaticCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig($key, $default)
    {
        if (!\is_string($key)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
    /**
     * {@inheritDoc}
     */
    public function setConfig($key, $value)
    {
        if (!\is_string($key)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($key) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($key) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->config[$key] = $value;
    }
    /**
     * {@inheritDoc}
     */
    public static function mergeConfigs(array ...$configs)
    {
        $final = [];
        foreach (\array_unique($configs, SORT_REGULAR) as $config) {
            foreach ($final as $k => $compare) {
                if (empty($intersect = \array_intersect_key($config, $compare)) || $intersect === \array_intersect_key($compare, $config)) {
                    $final[$k] = $config + $compare;
                    continue 2;
                }
            }
            $final[] = $config;
        }
        $phabelReturn = $final;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function splitConfig(array $config)
    {
        $phabelReturn = empty($config) ? [[]] : \array_chunk($config, 1, true);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public function getComposerRequires()
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function next(array $config)
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function previous(array $config)
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function withPrevious(array $config)
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function withNext(array $config)
    {
        $phabelReturn = [];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
