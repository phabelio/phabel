<?php

namespace Phabel\PluginGraph;

/**
 * List of packages associated with plugin.
 */
class PackageContext
{
    /**
     * Package list.
     *
     * @var array<string, null>
     */
    private array $packages = [];
    /**
     * Add package.
     *
     * @param string $package Package
     *
     * @return void
     */
    public function addPackage(string $package): void
    {
        $this->packages[$package] = null;
    }
    /**
     * Merge two contexts.
     *
     * @param self $other Other context
     *
     * @return self New context
     */
    public function merge(self $other): self
    {
        $this->packages += $other->packages;
        return $this;
    }

    /**
     * Get package list.
     *
     * @return array
     */
    public function getPackages(): array
    {
        return \array_values($this->packages);
    }
}
