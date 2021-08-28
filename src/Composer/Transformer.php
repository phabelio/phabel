<?php

namespace Phabel\Composer;

use Composer\IO\ConsoleIO;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\Constraint\MultiConstraint;
use Composer\Semver\VersionParser;
use Phabel\Cli\EventHandler as CliEventHandler;
use Phabel\Cli\Formatter;
use Phabel\EventHandler;
use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Traverser;
use ReflectionClass;
use Symfony\Component\Console\Helper\ProgressBar;

class Transformer extends EventHandler
{
    const HEADER = 'phabel/transpiler';
    const SEPARATOR = '/';
    /**
     * IO interface.
     */
    private $io;
    /**
     * IO interface.
     */
    private $outputFormatter;
    /**
     * Version parser.
     */
    private $versionParser;
    /**
     * Requires.
     */
    private $requires = [];
    /**
     * Whether we processed requirements.
     */
    private $processedRequires = [];
    /**
     * Whether we processed any dependencies.
     */
    private $processed = false;
    /**
     * Whether a progress bar should be shown.
     */
    private $doProgress = true;
    /**
     * Instance.
     */
    private static $instance;
    /**
     * Get singleton.
     *
     * @return self
     */
    public static function getInstance(IOInterface $io)
    {
        self::$instance = isset(self::$instance) ? self::$instance : new self($io);
        $phabelReturn = self::$instance;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Constructor.
     *
     * @param IOInterface $io
     */
    private function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->versionParser = new VersionParser();
        $this->outputFormatter = Formatter::getFormatter();
    }
    /**
     * Log text.
     *
     * @param string $text
     * @param int $verbosity
     * @param bool $newline
     * @return void
     */
    public function log($text, $verbosity = IOInterface::NORMAL, $newline = true)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $text = (string) $text;
        }
        if (!\is_int($verbosity)) {
            if (!(\is_bool($verbosity) || \is_numeric($verbosity))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($verbosity) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($verbosity) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $verbosity = (int) $verbosity;
        }
        if (!\is_bool($newline)) {
            if (!(\is_bool($newline) || \is_numeric($newline) || \is_string($newline))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($newline) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $newline = (bool) $newline;
        }
        $this->io->writeError($this->format("<phabel>{$text}</phabel>"), $newline, $verbosity);
    }
    /**
     * Format text.
     *
     * @param string $text
     * @return string
     */
    public function format($text)
    {
        if (!\is_string($text)) {
            if (!(\is_string($text) || \is_object($text) && \method_exists($text, '__toString') || (\is_bool($text) || \is_numeric($text)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $text = (string) $text;
        }
        $phabelReturn = $this->outputFormatter->format($text);
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Print banner.
     *
     * @return void
     */
    public function banner()
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
    public function preparePackage(PackageInterface &$package, $newName, $target = Php::TARGET_IGNORE)
    {
        if (!\is_string($newName)) {
            if (!(\is_string($newName) || \is_object($newName) && \method_exists($newName, '__toString') || (\is_bool($newName) || \is_numeric($newName)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($newName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $newName = (string) $newName;
        }
        if (!\is_int($target)) {
            if (!(\is_bool($target) || \is_numeric($target))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (int) $target;
        }
        /**
         * Phabel configuration of current package.
         * @var array
         */
        $config = null !== ($phabel_f0c212d00f8be226 = $package->getExtra()) && isset($phabel_f0c212d00f8be226['phabel']) ? $phabel_f0c212d00f8be226['phabel'] : [];
        $myTarget = Php::normalizeVersion(isset($config['target']) ? $config['target'] : Php::DEFAULT_TARGET);
        $havePhabel = false;
        foreach ($package->getRequires() as $link) {
            list($name) = $this->extractTarget($link->getTarget());
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
        foreach (isset($config['require']) ? $config['require'] : [] as $name => $constraint) {
            $requires[$this->injectTarget($name, $myTarget)] = $constraint;
        }
        if ($newName !== $package->getName() && \method_exists($package, 'setProvides')) {
            $package->setProvides(\array_merge($package->getProvides(), [$package->getName() => new Link($newName, $package->getName(), new ComposerConstraint('=', $package->getVersion()), Link::TYPE_PROVIDE, $package->getVersion())]));
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
    private function processRequires(PackageInterface $package, $target, array $requires, $havePhabel)
    {
        if (!\is_int($target)) {
            if (!(\is_bool($target) || \is_numeric($target))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (int) $target;
        }
        if (!\is_bool($havePhabel)) {
            if (!(\is_bool($havePhabel) || \is_numeric($havePhabel) || \is_string($havePhabel))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($havePhabel) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($havePhabel) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $havePhabel = (bool) $havePhabel;
        }
        $links = [];
        foreach ($package->getRequires() as $name => $link) {
            if (PlatformRepository::isPlatformPackage($link->getTarget())) {
                if ($link->getTarget() === 'php') {
                    $constraint = MultiConstraint::create([new ComposerConstraint('>=', Php::unnormalizeVersion($target)), new ComposerConstraint('<', Php::unnormalizeVersion($target + 1))]);
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
    private function injectTarget($package, $target)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $package = (string) $package;
        }
        if (!\is_int($target)) {
            if (!(\is_bool($target) || \is_numeric($target))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $target = (int) $target;
        }
        list($package) = $this->extractTarget($package);
        $phabelReturn = self::HEADER . $target . self::SEPARATOR . $package;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Look for phabel configuration parameters in constraint.
     *
     * @param string $package package name
     *
     * @return array{0: string, 1: int}
     */
    public function extractTarget($package)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $package = (string) $package;
        }
        if (\str_starts_with($package, self::HEADER)) {
            list($version, $package) = \explode(self::SEPARATOR, \substr($package, \strlen(self::HEADER)), 2);
            $phabelReturn = [$package, $version];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = [$package, Php::TARGET_IGNORE];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Transform dependencies.
     *
     * @param ?array $lock
     * @param ?array $old
     * @return bool Whether no more packages should be updated
     */
    public function transform($lock, $old)
    {
        if (!(\is_array($lock) || \is_null($lock))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($lock) must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($lock) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!(\is_array($old) || \is_null($old))) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($old) must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($old) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $enabled = \gc_enabled();
        \gc_enable();
        $packages = isset($lock['packages']) ? $lock['packages'] : [];
        $this->log("Creating plugin graph...", IOInterface::VERBOSE);
        $byName = [];
        foreach ($packages as $package) {
            list($name, $target) = $this->extractTarget($package['name']);
            if ($target === Php::TARGET_IGNORE) {
                continue;
            }
            $package['phabelTarget'] = (int) $target;
            $package['phabelConfig'] = [isset($package['extra']['phabel']) ? $package['extra']['phabel'] : []];
            unset($package['phabelConfig'][0]['target']);
            $byName[$name] = $package;
        }
        do {
            $changed = false;
            foreach ($byName as $name => $package) {
                $parentConfigs = $package['phabelConfig'];
                foreach (isset($package['require']) ? $package['require'] : [] as $subName => $constraint) {
                    if (PlatformRepository::isPlatformPackage($subName)) {
                        continue;
                    }
                    list($subName) = $this->extractTarget($subName);
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
        $traverser = new Traverser(new CliEventHandler($this->io, $this->doProgress && $this->io instanceof ConsoleIO && !\getenv('CI') && !$this->io->isDebug() ? function ($progress) {
            if (!\is_int($progress)) {
                if (!(\is_bool($progress) || \is_numeric($progress))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($progress) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($progress) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $progress = (int) $progress;
            }
            $phabelReturn = $this->io->getProgressBar($progress);
            if (!$phabelReturn instanceof ProgressBar) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ProgressBar, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } : null));
        $traverser->setPluginGraph($graph);
        unset($graph);
        $this->requires = $traverser->getGraph()->getPackages();
        if (!$this->processedRequires()) {
            if (!$enabled) {
                unset($traverser);
                while (\gc_collect_cycles()) {
                }
                \gc_disable();
            }
            $phabelReturn = false;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        if ($lock && $lock === $old) {
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (!$byName) {
            $phabelReturn = true;
            if (!\is_bool($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (bool) $phabelReturn;
            }
            return $phabelReturn;
        }
        $this->banner();
        $traverser->setInput('vendor')->setOutput('vendor')->setComposer(function ($rel) {
            if (!\is_string($rel)) {
                if (!(\is_string($rel) || \is_object($rel) && \method_exists($rel, '__toString') || (\is_bool($rel) || \is_numeric($rel)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($rel) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($rel) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $rel = (string) $rel;
            }
            list($package) = $this->extractTarget(\str_replace('\\', '/', $rel));
            $phabelReturn = \implode('/', \array_slice(\explode('/', $package), 0, 2));
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        })->run((int) (\getenv('PHABEL_PARALLEL') ?: 1));
        if (!$enabled) {
            unset($traverser);
            while (\gc_collect_cycles()) {
            }
            \gc_disable();
        }
        $phabelReturn = true;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get whether we processed any dependencies.
     *
     * @return bool
     */
    public function processedRequires()
    {
        $phabelReturn = $this->processed && $this->processedRequires === $this->requires;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Get IO interface.
     *
     * @return IOInterface
     */
    public function getIo()
    {
        $phabelReturn = $this->io;
        if (!$phabelReturn instanceof IOInterface) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type IOInterface, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
