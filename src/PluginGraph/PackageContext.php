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
    public function addPackage(string $package) : void
    {
        $this->packages[$package] = \true;
    }
    /**
     * Merge two contexts.
     *
     * @param self $other Other context
     *
     * @return self New context
     */
    public function merge(self $other) : self
    {
        $this->packages += $other->packages;
        return $this;
    }
    /**
     * Check if a package is present in the package context.
     *
     * @param string $package Package
     *
     * @return boolean
     */
    public function has(string $package) : bool
    {
        return isset($this->packages[$package]);
    }
    /**
     * Get package list.
     *
     * @return array
     */
    public function getPackages() : array
    {
        return \array_values($this->packages);
    }
}
