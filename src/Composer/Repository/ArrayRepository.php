<?php

namespace Phabel\Composer\Repository;

use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Repository\ArrayRepository as ComposerArrayRepository;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 *
 * @property ComposerArrayRepository $repository
 */
trait ArrayRepository
{
    public function addPackage(PackageInterface $package)
    {
        $this->repository->addPackage($package);
    }

    public function getProviders($packageName)
    {
        return $this->repository->getProviders($packageName);
    }
    public function loadPackages(array $packageNameMap, array $acceptableStabilities, array $stabilityFlags, array $alreadyLoaded = array())
    {
        $packages = $this->repository->loadPackages($packageNameMap, $acceptableStabilities, $stabilityFlags, $alreadyLoaded);
        foreach ($packages['packages'] as &$package) {
            self::preparePackage($package, []);
        }
        return $packages;
    }
    public function getPackageNames($packageFilter = null)
    {
        return $this->getPackageNames($packageFilter);
    }
}