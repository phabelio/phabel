<?php

namespace Phabel\Composer;

use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\ComposerRepository;
use Composer\Repository\RepositoryInterface;
use Composer\Repository\ConfigurableRepositoryInterface;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 *
 * @property ConfigurableRepositoryInterface $repository
 */
trait ConfigurableRepository
{
    /**
     * Get repository configuration.
     *
     * @return mixed
     */
    public function getRepoConfig()
    {
        return $this->repository->getRepoConfig();
    }
}