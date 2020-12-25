<?php

namespace Phabel\Composer;

use Composer\Package\PackageInterface;
use Phabel\Target\Php;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
trait Repository
{
    public Transformer $phabelTransformer;
    /**
     * TODO v3 should make this private once we can drop PHP 5.3 support.
     *
     * @param string $name package name (must be lowercased already)
     * @private
     */
    public function isVersionAcceptable($constraint, $name, $versionData, array $acceptableStabilities = null, array $stabilityFlags = null)
    {
        [$name] = $this->phabelTransformer->extractTarget($name);
        return parent::isVersionAcceptable($constraint, $name, $versionData, $acceptableStabilities, $stabilityFlags);
    }
    /**
     * Load packages.
     *
     * @param array $packageNameMap
     * @param array $acceptableStabilities
     * @param array $stabilityFlags
     * @param array $alreadyLoaded
     * @return void
     */
    public function loadPackages(array $packageNameMap, array $acceptableStabilities, array $stabilityFlags, array $alreadyLoaded = [])
    {
        $newPackages = [];
        $targets = [];
        $keyMap = [];
        foreach ($packageNameMap as $key => $constraint) {
            [$package, $target] = $this->phabelTransformer->extractTarget($key);
            $newPackages[$package] = $constraint;
            $targets[$package] = $target;
            $keyMap[$package] = $key;
        }

        $packages = parent::loadPackages($newPackages, $acceptableStabilities, $stabilityFlags, $alreadyLoaded);
        foreach ($packages['namesFound'] as &$name) {
            $name = $keyMap[$name];
        }
        foreach ($packages['packages'] as $key => &$package) {
            $this->phabelTransformer->preparePackage($package, $keyMap[$package->getName()], $targets[$package->getName()]);
        }
        return $packages;
    }

    /**
     * Searches for the first match of a package by name and version.
     *
     * @param string                                                 $name       package name
     * @param string|\Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return PackageInterface|null
     */
    public function findPackage($fullName, $constraint)
    {
        [$name, $target] = $this->phabelTransformer->extractTarget($fullName);
        if (!$package = parent::findPackage($name, $constraint)) {
            return null;
        }
        return $this->phabelTransformer->preparePackage($package, $fullName, $target);
    }

    /**
     * Searches for all packages matching a name and optionally a version.
     *
     * @param string                                                 $name       package name
     * @param string|\Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return PackageInterface[]
     */
    public function findPackages($fullName, $constraint = null)
    {
        [$name, $target] = $this->phabelTransformer->extractTarget($fullName);
        foreach ($packages = parent::findPackages($name, $constraint) as $package) {
            $this->phabelTransformer->preparePackage($package, $fullName, $target);
        }
        return $packages;
    }

    /**
     * Returns list of registered packages.
     *
     * @return PackageInterface[]
     */
    public function getPackages()
    {
        $packages = parent::getPackages();
        foreach ($packages as $package) {
            $this->phabelTransformer->preparePackage($package, $package->getName());
        }
        return $packages;
    }
}
