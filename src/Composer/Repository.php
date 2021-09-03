<?php

namespace Phabel\Composer;

use Composer\Package\PackageInterface;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
trait Repository
{
    private $phabelTransformer;
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
        $newAlreadyLoaded = [];
        $newPackageNameMap = [];
        $transformInfo = [];
        foreach ($packageNameMap as $key => $constraint) {
            [$package, $target] = $this->phabelTransformer->extractTarget($key);
            $newPackageNameMap[$target] = $newPackageNameMap[$target] ?? [];
            $newPackageNameMap[$target][$package] = $constraint;
            $transformInfo[$target] = $transformInfo[$target] ?? [];
            $transformInfo[$target][$package] = $key;
        }
        foreach ($alreadyLoaded as $key => $versions) {
            [$package, $target] = $this->phabelTransformer->extractTarget($key);
            $newAlreadyLoaded[$target] = $newAlreadyLoaded[$target] ?? [];
            $newAlreadyLoaded[$target][$package] = $versions;
        }
        $finalNamesFound = [];
        $finalPackages = [];
        foreach ($newPackageNameMap as $target => $map) {
            $t = $transformInfo[$target];
            $packages = parent::loadPackages($map, $acceptableStabilities, $stabilityFlags, $newAlreadyLoaded[$target] ?? []);
            foreach ($packages['namesFound'] as $package) {
                $finalNamesFound[] = $t[$package];
            }
            foreach ($packages['packages'] as $package) {
                $package = clone $package;
                $this->phabelTransformer->preparePackage($package, $t[$package->getName()], $target);
                $finalPackages[] = $package;
            }
        }
        $packages['namesFound'] = $finalNamesFound;
        $packages['packages'] = $finalPackages;
        /*$missing = \array_diff(\array_keys($packageNameMap), $finalNamesFound);
          if (!empty($missing)) {
              $this->phabelTransformer->getIo()->debug("Could not find the following packages in ".\get_parent_class($this).": ".\implode(", ", $missing));
          } else {
              $this->phabelTransformer->getIo()->debug("Loaded packages in ".\get_parent_class($this).": ".\implode(", ", $finalNamesFound));
          }*/
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
        if (!($package = parent::findPackage($name, $constraint))) {
            return null;
        }
        $package = clone $package;
        $this->phabelTransformer->preparePackage($package, $fullName, $target);
        return $package;
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
        $packages = parent::findPackages($name, $constraint);
        foreach ($packages as &$package) {
            $package = clone $package;
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
        foreach ($packages as &$package) {
            $package = clone $package;
            $this->phabelTransformer->preparePackage($package, $package->getName());
        }
        return $packages;
    }
    /**
     * Set the Transformer.
     *
     * @param Transformer $phabelTransformer
     *
     * @return self
     */
    public function setPhabelTransformer(Transformer $phabelTransformer): self
    {
        $this->phabelTransformer = $phabelTransformer;
        return $this;
    }
}
