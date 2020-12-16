<?php

namespace Phabel\Composer\Traits;

use Composer\Package\AliasPackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\Constraint\MatchAllConstraint;
use Composer\Semver\Constraint\MultiConstraint;
use Phabel\Target\Php;
use Phabel\Tools;

const SEPARATOR = ' ';
const HEADER = 'phabel ';

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
        self::processRequires($package, \json_encode($config));
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
    private static function processRequires(PackageInterface $package, string $config)
    {
        $links = [];
        foreach ($package->getRequires() as $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                continue;
            }
            //var_dumP($link->getTarget(), (string) $link->getConstraint());
            $links []= new Link(
                $link->getSource(),
                $link->getTarget(),
                self::injectConfig($link->getConstraint(), $config),
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

    /**
     * Inject config into constraint.
     *
     * @param ConstraintInterface $constraint
     * @param string $config
     * @return ConstraintInterface
     */
    private static function injectConfig(ConstraintInterface $constraint, string $config): ConstraintInterface
    {
        if ($constraint instanceof ComposerConstraint) {
            $version = $constraint->getVersion();
            if (\str_starts_with($version, HEADER)) {
                [$version] = \explode(SEPARATOR, \substr($version, \strlen(HEADER)), 2);
            }
            $version = HEADER.$version.SEPARATOR.$config;
            return new ComposerConstraint($constraint->getOperator(), $version);
        } elseif ($constraint instanceof MultiConstraint) {
            $constraints = $constraint->getConstraints();
            foreach ($constraints as &$cur) {
                $cur = self::injectConfig($cur, $config);
            }
            return new MultiConstraint($constraints, $constraint->isConjunctive());
        } elseif ($constraint instanceof MatchAllConstraint) {
            $version = HEADER."*".SEPARATOR.$config;
            return new ComposerConstraint('=', $version);
        }
        return $constraint;
    }

    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param \Composer\Semver\Constraint\ConstraintInterface $constraint package version or version constraint to match against
     *
     * @return ?array
     */
    public static function extractConfig(ConstraintInterface &$constraint): ?array
    {
        //var_dump($constraint);
        $config = null;
        if ($constraint instanceof ComposerConstraint) {
            $version = $constraint->getVersion();
            if (\str_starts_with($version, HEADER)) {
                \var_dump($version);
                [$version, $config] = \explode(SEPARATOR, \substr($version, \strlen(HEADER)), 2);
                //var_export($version);
                //var_export($config);
                //var_dump("========");
                $config = \json_decode($config, true);
                if ($config === false) {
                    $config = null;
                }
                $constraint = $version === '*'
                    ? new MatchAllConstraint
                    : new ComposerConstraint($constraint->getOperator(), $version);
            }
        } elseif ($constraint instanceof MultiConstraint) {
            $constraints = $constraint->getConstraints();
            foreach ($constraints as &$cur) {
                $config = self::trickleMergeConfig($config, self::extractConfig($cur));
            }
            $constraint = new MultiConstraint($constraints, $constraint->isConjunctive());
        }

        return $config;
    }


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
