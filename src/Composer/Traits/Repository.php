<?php

namespace Phabel\Composer\Traits;

use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\Constraint\MatchAllConstraint;
use Phabel\Target\Php;
use Phabel\Tools;
use ReflectionClass;

const HEADER = 'phabel-transpiled';
const SEPARATOR = '/../';

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
trait Repository
{
    /**
     * TODO v3 should make this private once we can drop PHP 5.3 support.
     *
     * @param string $name package name (must be lowercased already)
     * @private
     */
    public function isVersionAcceptable($constraint, $name, $versionData, array $acceptableStabilities = null, array $stabilityFlags = null)
    {
        self::extractConfig($name);
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
        $configs = [];
        $keyMap = [];
        foreach ($packageNameMap as $key => $constraint) {
            [$package, $config] = self::extractConfig($key);
            $newPackages[$package] = $constraint;
            $configs[$package] = $config;
            $keyMap[$package] = $key;
        }

        $packages = parent::loadPackages($newPackages, $acceptableStabilities, $stabilityFlags, $alreadyLoaded);
        foreach ($packages['namesFound'] as &$name) {
            $name = $keyMap[$name];
        }
        foreach ($packages['packages'] as $key => &$package) {
            self::preparePackage($package, $keyMap[$package->getName()], $configs[$package->getName()] ?? null);
        }
        return $packages;
    }
    /**
     * Prepare package.
     *
     * @param PackageInterface $package Package
     * @param ?array           $config  Configuration inherited from constraint
     *
     * @return void
     */
    public static function preparePackage(PackageInterface $package, string $newName, ?array $config): void
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
                $myConfig['target'] = Php::normalizeVersion($link->getConstraint()->getLowerBound()->getVersion());
                if ($havePhabel) {
                    break;
                }
            }
        }
        if (!$havePhabel) {
            $myConfig = [];
        }
        if (!$havePhabel && $config === null) {
            \var_dump("Skipping ".$package->getName());
            return;
        }
        \var_dump("Applying ".$package->getName());
        $config = self::trickleMergeConfig($config, $myConfig);
        self::processRequires(
            $package, 
            new ComposerConstraint('>=', Php::unnormalizeVersion($config['target'] ?? Php::DEFAULT_TARGET)), 
            bin2hex(\json_encode($config))
        );

        $base = new ReflectionClass(BasePackage::class);
        $method = $base->getMethod('__construct');
        $method->invokeArgs($package, [$newName]);
    }

    /**
     * Merge configs.
     *
     * @param array|null $prev Config trickled from above
     * @param array|null $new  Current config
     * @return array|null
     */
    private static function trickleMergeConfig(?array $prev, ?array $new): ?array
    {
        if ($prev === null || empty($prev)) {
            return $new;
        }
        return $prev;
    }
    /**
     * Add phabel config to all requires.
     *
     * @param PackageInterface $package
     * @param string $config
     * @return void
     */
    private static function processRequires(PackageInterface $package, ComposerConstraint $target, string $config)
    {
        $links = [];
        foreach ($package->getRequires() as $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                if ($link->getTarget() === 'php') {
                    $links []= new Link(
                        $link->getSource(),
                        $link->getTarget(),
                        $target,
                        $link->getDescription(),
                        $target->getPrettyString()
                    );
                } else {
                    $links []= $link;
                }
                continue;
            }
            $links []= new Link(
                $link->getSource(),
                self::injectConfig($link->getTarget(), $config),
                $link->getConstraint(),
                $link->getDescription(),
                $link->getPrettyConstraint()
            );
        }
        if ($package instanceof Package) {
            $package->setRequires($links);
        } elseif ($package instanceof AliasPackage) {
            Tools::setVar($package, 'requires', $links);
            self::processRequires($package->getAliasOf(), $target, $config);
        }
    }

    /**
     * Inject config into package name.
     *
     * @param string $package
     * @param string $config
     * @return string
     */
    private static function injectConfig(string $package, string $config): string
    {
        if (str_starts_with($package, HEADER)) {
            [, $package] = \explode(SEPARATOR, $package, 2);
        }
        if ($config === '5b5d') { // bin2hex('[]')
            $config = 'default';
        }
        return HEADER.$config.SEPARATOR.$package;
    }

    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param string $constraint package version or version constraint to match against
     *
     * @return ?array
     */
    public static function extractConfig(string $package): array
    {
        if (str_starts_with($package, HEADER)) {
            $parts = \explode(SEPARATOR, $package, 2);
            $config = $parts[0];
            $package = $parts[1].'/'.$parts[2];
            if ($config === 'default') {
                return [$package, []];
            }
            $config = \json_decode(hex2bin($config), true);
            if ($config === false) {
                $config = null;
            }
            return [$package, $config];
        }
        return [$package, null];
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
        [$name, $config] = self::extractConfig($fullName);
        if (!$package = parent::findPackage($name, $constraint)) {
            return null;
        }
        return self::preparePackage($package, $fullName, $config);
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
        [$name, $config] = self::extractConfig($fullName);
        foreach ($packages = parent::findPackages($name, $constraint) as $package) {
            self::preparePackage($package, $fullName, $config);
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
            self::preparePackage($package, $package->getName(), null);
        }
        return $packages;
    }
}
