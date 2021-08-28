<?php

namespace Phabel\PluginGraph;

/**
 * List of packages associated with plugin.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class PackageContext
{
    /**
     * Package list.
     *
     * @var array<string, null>
     */
    private $packages = [];
    /**
     * Add package.
     *
     * @param string $package Package
     *
     * @return void
     */
    public function addPackage($package)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $package = (string) $package;
        }
        $this->packages[$package] = true;
    }
    /**
     * Merge two contexts.
     *
     * @param self $other Other context
     *
     * @return self New context
     */
    public function merge(self $other)
    {
        $this->packages += $other->packages;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Check if a package is present in the package context.
     *
     * @param string $package Package
     *
     * @return boolean
     */
    public function has($package)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $package = (string) $package;
        }
        $phabelReturn = isset($this->packages[$package]);
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get package list.
     *
     * @return array
     */
    public function getPackages()
    {
        $phabelReturn = \array_values($this->packages);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
