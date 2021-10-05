<?php

namespace Phabel\Composer;

use Composer\Installer\InstallationManager;
use Composer\IO\ConsoleIO;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\Constraint\MatchAllConstraint;
use Composer\Semver\VersionParser;
use Phabel\Cli\Formatter;
use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Tools;
use Phabel\Traverser;
use ReflectionClass;
use Symfony\Component\Filesystem\Filesystem;

class Transformer
{
    const HEADER = 'phabel.transpiler';
    const SEPARATOR = ':';
    /**
     * IO interface.
     */
    private $outputFormatter;
    /**
     * Version parser.
     */
    private VersionParser $versionParser;
    /**
     * Requires.
     */
    private array $requires = [];
    /**
     * Whether we processed requirements.
     */
    private array $processedRequires = [];
    /**
     * Whether we processed any dependencies.
     */
    private bool $processed = false;
    /**
     * Whether a progress bar should be shown.
     */
    private bool $doProgress = true;
    /**
     * Installer.
     *
     * @var InstallationManager
     */
    private InstallationManager $installer;
    /**
     * Instance.
     */
    private static self $instance;
    /**
     * Get singleton.
     *
     * @return self
     */
    public static function getInstance(IOInterface $io, int $version): self
    {
        self::$instance ??= new self($io, $version);
        return self::$instance;
    }
    /**
     * Constructor.
     *
     * @param IOInterface $io
     */
    private function __construct(private IOInterface $io, private int $version)
    {
        $this->versionParser = new VersionParser();
        $this->outputFormatter = Formatter::getFormatter();
    }
    /**
     * Set installation manager.
     *
     * @param InstallationManager $installationManager
     * @return void
     */
    public function setInstallationManager(InstallationManager $installationManager): void
    {
        $this->installer = $installationManager;
    }
    /**
     * Log text.
     *
     * @param string $text
     * @param int $verbosity
     * @param bool $newline
     * @return void
     */
    public function log(string $text, int $verbosity = IOInterface::NORMAL, bool $newline = true): void
    {
        $this->io->writeError($this->format("<phabel>{$text}</phabel>"), $newline, $verbosity);
    }
    /**
     * Format text.
     *
     * @param string $text
     * @return string
     */
    public function format(string $text): string
    {
        return $this->outputFormatter->format($text);
    }
    /**
     * Print banner.
     *
     * @return void
     */
    public function banner(): void
    {
        static $printed = false;
        if (!$printed) {
            $printed = true;
            $this->log(PHP_EOL . Formatter::BANNER . PHP_EOL);
        }
    }
    /**
     * Prepare package for phabel tree injection.
     *
     * @param PackageInterface $package Package
     * @param string           $newName New package name
     * @param int              $target  Target
     *
     * @return void
     */
    public function preparePackage(PackageInterface &$package, string $newName, int $target = Php::TARGET_IGNORE): void
    {
        /**
         * Phabel configuration of current package.
         * @var array
         */
        $config = $package->getExtra()['phabel'] ?? [];
        $myTarget = Php::normalizeVersion($config['target'] ?? $this->version);
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
        $this->processed = true;
        if (!$havePhabel) {
            if ($target === Php::TARGET_IGNORE) {
                $this->log("Skipping " . $package->getName() . "={$newName}", IOInterface::VERY_VERBOSE);
                return;
            }
            $myTarget = $target;
        } else {
            $myTarget = Php::normalizeVersion($myTarget);
            $myTarget = \min($myTarget, $target);
        }
        $this->log("Applying " . $package->getName() . "={$newName}", IOInterface::VERY_VERBOSE);
        $this->processedRequires = $this->requires;
        $requires = $this->requires;
        foreach ($config['require'] ?? [] as $name => $constraint) {
            $requires[$this->injectTarget($name, $myTarget)] = $constraint;
        }
        if ($newName !== $package->getName()) {
            if (\method_exists($package, 'setProvides')) {
                $package->setProvides(\array_merge($package->getProvides(), [$package->getName() => new Link($newName, $package->getName(), new ComposerConstraint('=', $package->getVersion()), Link::TYPE_PROVIDE, $package->getVersion())]));
            }
            if (\method_exists($package, 'setConflicts')) {
                $package->setConflicts(\array_merge($package->getConflicts(), [$package->getName() => new Link($newName, $package->getName(), new MatchAllConstraint(), Link::TYPE_CONFLICT, '*')]));
            }
        }
        $base = new ReflectionClass(BasePackage::class);
        $method = $base->getMethod('__construct');
        $method->invokeArgs($package, [$newName]);
        $this->processRequires($package, $myTarget, $requires, $havePhabel);
    }
    /**
     * Add phabel config to all requires.
     *
     * @param PackageInterface $package
     * @param int $target
     * @param array $config
     * @param bool $havePhabel
     *
     * @return void
     */
    private function processRequires(PackageInterface $package, int $target, array $requires, bool $havePhabel)
    {
        $links = [];
        foreach ($package->getRequires() as $name => $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                if ($link->getTarget() === 'php') {
                    $constraint = new ComposerConstraint('>=', Php::unnormalizeVersion($target));
                    $links[$name] = new Link($package->getName(), $link->getTarget(), $constraint, $link->getDescription(), $constraint->getPrettyString());
                } else {
                    $links[$name] = $link;
                }
                continue;
            }
            $links[$name] = new Link($package->getName(), $havePhabel ? $link->getTarget() : $this->injectTarget($link->getTarget(), $target), $link->getConstraint(), $link->getDescription(), $link->getPrettyConstraint());
        }
        foreach ($requires as $name => $version) {
            $links[$name] = new Link($package->getName(), $name, $this->versionParser->parseConstraints($version), Link::TYPE_REQUIRE, $version);
        }
        if ($package instanceof Package) {
            $package->setRequires($links);
        } elseif ($package instanceof AliasPackage) {
            $this->processRequires($package->getAliasOf(), $target, $requires, $havePhabel);
        }
    }
    /**
     * Inject target into package name.
     *
     * @param string $package
     * @param int $target
     * @return string
     */
    public static function injectTarget(string $package, int $target): string
    {
        [$package] = self::extractTarget($package);
        return self::HEADER . $target . self::SEPARATOR . $package;
    }
    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param string $package package name
     *
     * @return array{0: string, 1: int}
     */
    public static function extractTarget(string $package): array
    {
        if (\str_starts_with($package, self::HEADER)) {
            [$version, $package] = \explode(self::SEPARATOR, \substr($package, \strlen(self::HEADER)), 2);
            return [$package, $version];
        }
        return [$package, Php::TARGET_IGNORE];
    }
    /**
     * Transform dependencies.
     *
     * @param ?array $lock
     * @param ?array $old
     * @return bool Whether any additional packages should be installed or updated
     */
    public function transform(?array $lock, ?array $old): bool
    {
        $enabled = \gc_enabled();
        \gc_enable();
        $filesystem = new Filesystem();
        $packages = $lock['packages'] ?? [];
        $this->log("Creating plugin graph...", IOInterface::VERBOSE);
        $missingDeps = false;
        $paths = [];
        $byName = [];
        foreach ($packages as $package) {
            $config = $package['extra']['phabel'] ?? [];
            $myTarget = Php::normalizeVersion($config['target'] ?? $this->version);
            $havePhabel = false;
            $have = [];
            foreach ($package['require'] ?? [] as $name => $version) {
                [$name] = $this->extractTarget($name);
                $have[$name] = $version;
                if ($name === 'phabel/phabel') {
                    $havePhabel = true;
                }
            }
            foreach ($config['require'] ?? [] as $name => $version) {
                [$name] = $this->extractTarget($name);
                if (!isset($have[$name])) {
                    $missingDeps = true;
                }
            }
            [$name, $target] = $this->extractTarget($package['name']);
            if ($target === Php::TARGET_IGNORE) {
                if (!$havePhabel) {
                    continue;
                }
                $target = $myTarget;
            }
            try {
                $installer = $this->installer->getInstaller($package['type']);
                $p = new Package($package['name'], $package['version'], $package['version']);
                $p->setType($package['type']);
                $current = $installer->getInstallPath($p);
                if (!$filesystem->isAbsolutePath($current)) {
                    $current = \getcwd() . DIRECTORY_SEPARATOR . $current;
                }
                $p = new Package($name, $package['version'], $package['version']);
                $p->setType($package['type']);
                $new = $installer->getInstallPath($p);
                if (!$filesystem->isAbsolutePath($new)) {
                    $new = \getcwd() . DIRECTORY_SEPARATOR . $new;
                }
                $paths[$name] = [\rtrim(\str_replace('\\', '/', $current), '/'), \rtrim(\str_replace('\\', '/', $new), '/')];
            } catch (\Throwable $e) {
            }
            $package['phabelTarget'] = (int) $target;
            $package['phabelConfig'] = [$config];
            unset($package['phabelConfig'][0]['target']);
            $byName[$name] = $package;
        }
        do {
            $changed = false;
            foreach ($byName as $name => $package) {
                $parentConfigs = $package['phabelConfig'];
                foreach ($package['require'] ?? [] as $subName => $constraint) {
                    if (PlatformRepository::isPlatformPackage($subName)) {
                        continue;
                    }
                    [$subName, $target] = $this->extractTarget($subName);
                    if ($target === Php::TARGET_IGNORE) {
                        continue;
                    }
                    foreach ($parentConfigs as $config) {
                        if (!\in_array($config, $byName[$subName]['phabelConfig'])) {
                            $byName[$subName]['phabelConfig'][] = $config;
                            $changed = true;
                        }
                    }
                }
            }
        } while ($changed);
        $graph = new Graph();
        foreach ($byName as $name => $package) {
            $ctx = $graph->getPackageContext();
            $ctx->addPackage($name);
            $target = ['target' => $package['phabelTarget']];
            foreach ($package['phabelConfig'] as $config) {
                $graph->addPlugin(Php::class, $config + $target, $ctx);
            }
        }
        $traverser = new Traverser(new EventHandler($this->io, $this->doProgress && $this->io instanceof ConsoleIO && !\getenv('CI') && !$this->io->isDebug() ? fn (int $progress) => $this->io->getProgressBar($progress) : null));
        $traverser->setPluginGraph($graph);
        unset($graph);
        static $lastTry;
        $this->requires = $traverser->getGraph()->getPackages();
        unset($this->requires['phabel/phabel']);
        if (!$this->processedRequires() && $lastTry !== $this->requires) {
            $lastTry = $this->requires;
            if (!$enabled) {
                unset($traverser);
                while (\gc_collect_cycles()) {
                }
                \gc_disable();
            }
            return true;
        }
        if ($lock && $lock === $old) {
            return $missingDeps;
        }
        if (!$byName) {
            return $missingDeps;
        }
        $this->banner();
        $traverser->setComposerPaths($paths)->run((int) (\getenv('PHABEL_PARALLEL') ?: 1));
        foreach ($paths as [$old, $new]) {
            Tools::traverseCopy($new, $old);
        }
        if (!$enabled) {
            unset($traverser);
            while (\gc_collect_cycles()) {
            }
            \gc_disable();
        }
        return $missingDeps;
    }
    /**
     * Get whether we processed any dependencies.
     *
     * @return bool
     */
    public function processedRequires(): bool
    {
        return $this->processed && $this->processedRequires === $this->requires;
    }
    /**
     * Get IO interface.
     *
     * @return IOInterface
     */
    public function getIo(): IOInterface
    {
        return $this->io;
    }
}
