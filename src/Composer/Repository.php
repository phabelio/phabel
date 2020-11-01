<?php

namespace Phabel\Composer;

use Composer\DependencyResolver\Pool;
use Composer\Package\Link;
use Composer\Package\PackageInterface;
use Composer\Repository\ComposerRepository;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Repository extends ComposerRepository
{
    /**
     * Configuration prefix.
     */
    private const CONFIG_PREFIX = 'phabel-config';
    /**
     * Previous repository .
     */
    private ComposerRepository $repository;
    /**
     * Constructor.
     *
     * @param RepositoryInterface[] $repositories Previous repositories
     */
    public function __construct(ComposerRepository $repository)
    {
        $this->repository = $repository;
        $this->packages = [];
    }

    /**
     * Get repository configuration.
     *
     * @return mixed
     */
    public function getRepoConfig()
    {
        return $this->repository->getRepoConfig();
    }
    /**
     * Set root aliases.
     *
     * @param array $rootAliases Root aliases
     *
     * @return void
     */
    public function setRootAliases(array $rootAliases): void
    {
        $this->repository->setRootAliases($rootAliases);
    }

    /**
     * Checks if specified package registered (installed).
     *
     * @param PackageInterface $package package instance
     *
     * @return bool
     */
    public function hasPackage(PackageInterface $package): bool
    {
        return $this->repository->hasPackage($package);
    }

    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param string|\Composer\Semver\Constraint\ConstraintInterface|null &$constraint package version or version constraint to match against
     *
     * @return array
     */
    private static function prepareConstraint(&$constraint): array
    {
        if (!$constraint instanceof ConstraintInterface && !\is_string($constraint)) {
            return [];
        }
        $constraint = (string) $constraint;
        if (!str_starts_with($constraint, self::CONFIG_PREFIX)) {
            return [];
        }
        [$config, $constraint] = \explode("\n", $constraint, 2);
        return \json_decode(\substr($config, 0, \strlen(self::CONFIG_PREFIX)), true) ?: [];
    }
    /**
     * Prepare package.
     *
     * @param PackageInterface $package Package
     * @param array            $config  Configuration inherited from constraint
     *
     * @return void
     */
    private static function preparePackage(PackageInterface $package, array $config): void
    {
        /**
         * Phabel configuration of current package.
         * @var array
         */
        $myConfig = $package->getExtra()['phabel'] ?? [];
        $havePhabel = false;
        foreach ($package->getRequires() as $link) {
            if ($link->getTarget() === 'phabel/phabel') {
                $havePhabel = true;
            }
            if ($link->getTarget() === 'php') {
                $myConfig['target'] = $link->getConstraint();
                if ($havePhabel) {
                    break;
                }
            }
        }
        if (!$havePhabel) {
            return;
        }
        // Config merging logic here...
        $links = [];
        foreach ($package->getRequires() as $link) {
            $version = self::CONFIG_PREFIX.\json_encode($config)."\n".($link->getConstraint() ?? '');
            $links []= new Link($link->getSource(), $link->getTarget(), new Constraint('>=', $version), $link->getDescription());
        }
        $package->setRequires($links);
    }

    /**
     * Searches for the first match of a package by name and version.
     *
     * @param string                                                 $name       package name
     * @param string|\Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return PackageInterface|null
     */
    public function findPackage(string $name, $constraint): ?PackageInterface
    {
        $config = self::prepareConstraint($constraint);
        if (!$package = $this->repository->findPackage($name, $constraint)) {
            return null;
        }
        return self::preparePackage($package, $config);
    }

    /**
     * Searches for all packages matching a name and optionally a version.
     *
     * @param string                                                 $name       package name
     * @param string|\Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return PackageInterface[]
     */
    public function findPackages($name, $constraint = null)
    {
        $config = self::prepareConstraint($constraint);
        foreach ($packages = $this->repository->findPackages($name, $constraint) as $package) {
            self::preparePackage($package, $config);
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
        $packages = $this->repository->getPackages();
        foreach ($packages as $package) {
            self::preparePackage($package, []);
        }
        return $packages;
    }

    /**
     * {@inheritDoc}
     */
    public function search($query, $mode = 0, $type = null)
    {
        return $this->repository->search($query, $mode, $type);
    }

    public function getProviderNames()
    {
        return $this->repository->getProviderNames();
    }

    public function hasProviders()
    {
        return $this->repository->hasProviders();
    }
    public function resetPackageIds()
    {
        return $this->repository->resetPackageIds();
    }
    public function addPackage(PackageInterface $package)
    {
        $this->repository->addPackage($package);
    }
    /**
     * @param  Pool        $pool
     * @param  string      $name          package name
     * @param  bool        $bypassFilters If set to true, this bypasses the stability filtering, and forces a recompute without cache
     * @return array|mixed
     */
    public function whatProvides(Pool $pool, $name, $bypassFilters = false)
    {
        $whatProvides = $this->repository->whatProvides($pool, $name, $bypassFilters);
        foreach ($whatProvides as $package => &$versions) {
            foreach ($versions as &$version) {
                if (!isset($version['require']['phabel/phabel'])) {
                    continue;
                }
                $config = $version['extra']['phabel'] ?? [];
                if (!isset($config['target']) && isset($version['require']['php'])) {
                    $config['target'] = $version['require']['php'];
                }
                foreach ($version['require'] as $package => &$version) {
                    $version = self::CONFIG_PREFIX.\json_encode($config)."\n".$version;
                }
            }
        }
        return $whatProvides;
    }
}
