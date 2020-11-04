<?php

namespace Phabel\Composer\Traits;

use Composer\Package\AliasPackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\Constraint\MultiConstraint;
use Phabel\Composer\PhabelConstraintInterface;
use Phabel\Tools;

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
        self::extractConfig($constraint);
        return parent::isVersionAcceptable($constraint, $name, $versionData, $acceptableStabilities, $stabilityFlags);
    }
    public function loadPackages(array $packageNameMap, array $acceptableStabilities, array $stabilityFlags, array $alreadyLoaded = [])
    {
        $configs = [];
        foreach ($packageNameMap as $key => &$constraint) {
            $configs[$key] = self::extractConfig($constraint);
        }

        $packages = parent::loadPackages($packageNameMap, $acceptableStabilities, $stabilityFlags, $alreadyLoaded);
        foreach ($packages['packages'] as &$package) {
            self::preparePackage($package, $configs[$package->getName()] ?? null);
        }
        return $packages;
    }

    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param \Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return ?array
     */
    public static function extractConfig(&$constraint): ?array
    {
        $config = null;
        if ($constraint instanceof PhabelConstraintInterface) {
            $config = $constraint->getConfig();
        } elseif ($constraint instanceof ComposerConstraint) {
            $version = $constraint->getVersion();
            if (str_starts_with($version, 'phabel')) {
                [$version, $config] = \explode("\0", \substr($version, 6));
                $config = \json_decode($config, true);
                $constraint = new ComposerConstraint($constraint->getOperator(), $version);
            }
        } elseif ($constraint instanceof MultiConstraint) {
            $constraints = $constraint->getConstraints() ;
            foreach ($constraints as &$cur) {
                $config = self::trickleMergeConfig($config, self::extractConfig($cur));
            }
            $constraint = new MultiConstraint($constraints, $constraint->isConjunctive());
        }
        return $config;
        /*
        if (!$constraint instanceof ConstraintInterface && !\is_string($constraint)) {
            return [];
        }
        $constraint = (string) $constraint;
        if (!str_starts_with($constraint, ComposerRepository::CONFIG_PREFIX)) {
            return [];
        }
        [$config, $constraint] = \explode("\0", $constraint, 2);
        return \json_decode(\substr($config, 0, \strlen(ComposerRepository::CONFIG_PREFIX)), true) ?: [];*/
    }
    private static function trickleMergeConfig(?array $prev, ?array $new): ?array
    {
        return $new;
    }
    /**
     * Prepare package.
     *
     * @param PackageInterface $package Package
     * @param ?array           $config  Configuration inherited from constraint
     *
     * @return void
     */
    public static function preparePackage(PackageInterface $package, ?array $config): void
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
        if (!$havePhabel && $config === null) {
            \var_dump("Skipping ".$package->getName());
            return;
        }
        \var_dump("Applying ".$package->getName());
        // Config merging logic here...
        if ($config === null || empty($config)) {
            $config = $myConfig;
        }
        self::processRequires($package, $config);
    }

    private static function processRequires(PackageInterface $package, array $config)
    {
        $links = [];
        foreach ($package->getRequires() as $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                continue;
            }
            //var_dumP($link->getTarget(), (string) $link->getConstraint());
            $constraint = $link->getConstraint();
            if ($constraint instanceof ComposerConstraint) {
                $version = $constraint-
            }
            if ($constraint instanceof PhabelConstraintInterface) {
                $constraint = clone $constraint;
            } else {
                $constraint = Tools::cloneWithTrait($constraint, Constraint::class, PhabelConstraintInterface::class);
            }
            $constraint->setConfig($config);
            $links []= new Link(
                $link->getSource(),
                $link->getTarget(),
                $constraint,
                $link->getDescription(),
                $link->getPrettyConstraint()
            );
        }
        if ($package instanceof Package) {
            $package->setRequires($links);
        } elseif ($package instanceof AliasPackage) {
            Tools::setVar($package, 'requires', $links);
            self::processRequires($package->getAliasOf(), $config);
        }
    }

    private static function injectConstraint

    /**
     * Searches for the first match of a package by name and version.
     *
     * @param string                                                 $name       package name
     * @param string|\Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return PackageInterface|null
     */
    public function findPackage($name, $constraint)
    {
        $config = self::extractConfig($constraint);
        if (!$package = parent::findPackage($name, $constraint)) {
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
        $config = self::extractConfig($constraint);
        foreach ($packages = parent::findPackages($name, $constraint) as $package) {
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
        $packages = parent::getPackages();
        foreach ($packages as $package) {
            self::preparePackage($package, null);
        }
        return $packages;
    }
}
