<?php

namespace Phabel\Composer;

use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Phabel\Target\Php;
use Phabel\Tools;
use ReflectionClass;

use Composer\IO\IOInterface;
use Exception;
use Phabel\PluginGraph\Graph;

class Transformer
{
    const HEADER = 'phabel/transpiler';
    const SEPARATOR = '/';
    /**
     * IO interface.
     */
    private IOInterface $io;

    /**
     * Constructor
     *
     * @param IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * Prepare package for phabel tree injection.
     *
     * @param PackageInterface $package Package
     * @param string           $newName New package name
     * @param 
     *
     * @return void
     */
    public function preparePackage(PackageInterface $package, string $newName, int $target = Php::TARGET_IGNORE): void
    {
        /**
         * Phabel configuration of current package.
         * @var array
         */
        $myTarget = $package->getExtra()['phabel']['target'] ?? Php::DEFAULT_TARGET;
        $havePhabel = false;
        foreach ($package->getRequires() as $link) {
            [$name] = $this->extractTarget($link->getTarget());
            if ($name === 'phabel/phabel') {
                $havePhabel = true;
            }
            if ($link->getTarget() === 'php') {
                $myTarget = $link->getConstraint()->getLowerBound()->getVersion();
                if ($havePhabel) {
                    break;
                }
            }
        }
        if (!$havePhabel) {
            if ($target === Php::TARGET_IGNORE) {
                return;
            }
            $myTarget = $target;
        } else {
            $myTarget = Php::normalizeVersion($myTarget);
            $myTarget = min($myTarget, $target);
        }
        \var_dump("Applying ".$package->getName());

        $this->processRequires(
            $package,
            $myTarget
        );

        $base = new ReflectionClass(BasePackage::class);
        $method = $base->getMethod('__construct');
        $method->invokeArgs($package, [$newName]);
    }

    /**
     * Add phabel config to all requires.
     *
     * @param PackageInterface $package
     * @param int $target
     * @return void
     */
    private function processRequires(PackageInterface $package, int $target)
    {
        $links = [];
        foreach ($package->getRequires() as $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                if ($link->getTarget() === 'php') {
                    $constraint = new ComposerConstraint('>=', Php::unnormalizeVersion($target));
                    $links []= new Link(
                        $link->getSource(),
                        $link->getTarget(),
                        $constraint,
                        $link->getDescription(),
                        $constraint->getPrettyString()
                    );
                } else {
                    $links []= $link;
                }
                continue;
            }
            $links []= new Link(
                $link->getSource(),
                $this->injectTarget($link->getTarget(), $target),
                $link->getConstraint(),
                $link->getDescription(),
                $link->getPrettyConstraint()
            );
        }
        if ($package instanceof Package) {
            $package->setRequires($links);
        } elseif ($package instanceof AliasPackage) {
            Tools::setVar($package, 'requires', $links);
            $this->processRequires($package->getAliasOf(), $target);
        }
    }

    /**
     * Inject target into package name.
     *
     * @param string $package
     * @param int $target
     * @return string
     */
    private function injectTarget(string $package, int $target): string
    {
        [$package] = $this->extractTarget($package);
        return self::HEADER.$target.self::SEPARATOR.$package;
    }

    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param string $package package name
     *
     * @return array{0: string, 1: int}
     */
    public function extractTarget(string $package): array
    {
        if (str_starts_with($package, self::HEADER)) {
            [$version, $package] = \explode(self::SEPARATOR, substr($package, strlen(self::HEADER)), 2);
            return [$package, $version];
        }
        return [$package, Php::TARGET_IGNORE];
    }

    /**
     * Transform dependencies
     *
     * @param array $lock
     * @return void
     */
    public function transform(array $lock) {
        $byName = [];
        foreach ($lock['packages'] as $package) {
            [$name, $target] = $this->extractTarget($package['name']);
            if ($target === Php::TARGET_IGNORE) {
                continue;
            }
            $package['phabelTarget'] = (int) $target;
            $package['phabelConfig'] = $package['extra']['phabel'] ?? [];
            unset($package['phabelConfig']['target']);            
            $byName[$name] = $package;
        }
        do {
            $changed = false;
            foreach ($byName as $name => $package) {
                $parentConfig = $package['phabelConfig'];
                foreach ($package['require'] ?? [] as $subName => $constraint) {
                    if (PlatformRepository::isPlatformPackage($subName)) {
                        continue;
                    }
                    [$subName] = $this->extractTarget($subName);
                    if ($target === Php::TARGET_IGNORE) {
                        continue;
                    }
                    $config = $byName[$subName]['phabelConfig'];
                    $byName[$subName]['phabelConfig'] = array_merge($parentConfig, $config);
                    if ($byName[$subName]['phabelConfig'] !== $config) {
                        $changed = true;
                    }
                }
            }
        } while ($changed);
        var_dump($byName);
    }
}