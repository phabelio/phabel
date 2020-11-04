<?php

namespace Phabel\Composer\Repository;

use Composer\DependencyResolver\Pool;
use Composer\Package\PackageInterface;
use Composer\Repository\ComposerRepository as ComposerComposerRepository;
use Composer\Repository\RepositoryInterface;
use ReflectionObject;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 * 
 * @property ComposerComposerRepository $repository
 */
class ComposerRepository extends ComposerComposerRepository
{
    use Repository;
    use ArrayRepository;
    use ConfigurableRepository;
    /**
     * Configuration prefix.
     */
    const CONFIG_PREFIX = 'phabel-config';

    /**
     * @param  Pool        $pool
     * @param  string      $name          package name
     * @param  bool        $bypassFilters If set to true, this bypasses the stability filtering, and forces a recompute without cache
     * @return array|mixed
     */
    /*
    public function whatProvides(Pool $pool, $name, $bypassFilters = false)
    {
        $whatProvides = $this->reflect->getMethod('whatProvides')->invokeArgs($this->repository, [$pool, $name, $bypassFilters]);
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
    }*/


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

    public function createPackages(array $packages, $class = 'Composer\Package\CompletePackage')
    {
        return $this->repository->createPackages($packages, $class);
    }

    /**
     * TODO v3 should make this private once we can drop PHP 5.3 support
     *
     * @param string $name package name (must be lowercased already)
     * @private
     */
    public function isVersionAcceptable($constraint, $name, $versionData, array $acceptableStabilities = null, array $stabilityFlags = null)
    {
        self::prepareConstraint($constraint);
        return $this->isVersionAcceptable($constraint, $name, $versionData, $acceptableStabilities, $stabilityFlags);
    }
}
