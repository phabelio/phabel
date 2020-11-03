<?php

namespace Phabel\Composer;

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
}