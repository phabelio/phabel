<?php

namespace Phabel\Composer;

use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\Link;
use Composer\Package\Package;
use Composer\Package\PackageInterface;
use Composer\Repository\PlatformRepository;
use Composer\Semver\Constraint\Constraint as ComposerConstraint;
use Composer\Semver\VersionParser;
use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Traverser;
use ReflectionClass;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Transformer
{
    const PHABEL = "\n<bold>＊＊＊＊＊＊＊＊＊</>\n<bold>＊</bold><phabel> Ｐｈａｂｅｌ </><bold>＊</bold>\n<bold>＊＊＊＊＊＊＊＊＊</>\n";
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
        $this->outputFormatter = new OutputFormatter(true, ['bold' => new OutputFormatterStyle('white', 'default', ['bold']), 'phabel' => new OutputFormatterStyle('blue', 'default', ['bold'])]);
    }
    /**
     * Log text.
     *
     * @param string $text
     * @param bool $format
     * @return void
     */
    public function log($text, $format = true)
    {
        if (!\is_string($text)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($text) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($text) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $blue = $this->outputFormatter->format($format ? "<phabel>{$text}</phabel>" : $text);
        $this->io->writeError($blue);
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
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($newName) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($newName) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($target)) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /**
         * Phabel configuration of current package.
         * @var array
         */
        $config = null !== ($phabel_c0be67b5ed033ca9 = $package->getExtra()) && isset($phabel_c0be67b5ed033ca9['phabel']) ? $phabel_c0be67b5ed033ca9['phabel'] : [];
        $myTarget = Php::normalizeVersion(isset($config['target']) ? $config['target'] : Php::DEFAULT_TARGET);
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
                $this->io->debug("Skipping " . $package->getName() . "={$newName}");
                return;
            }
            $myTarget = $target;
        } else {
            $myTarget = Php::normalizeVersion($myTarget);
            $myTarget = \min($myTarget, $target);
        }
        $this->io->debug("Applying " . $package->getName() . "={$newName}");
        $this->processed = true;
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
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_bool($havePhabel)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($havePhabel) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($havePhabel) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
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
    private function injectTarget($package, $target)
    {
        if (!\is_string($package)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_int($target)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($target) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($target) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        [$package] = $this->extractTarget($package);
        $phabelReturn = self::HEADER . $target . self::SEPARATOR . $package;
        if (!\is_string($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (\str_starts_with($package, self::HEADER)) {
            [$version, $package] = \explode(self::SEPARATOR, \substr($package, \strlen(self::HEADER)), 2);
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
     * @param array $packages
     * @return bool Whether no more packages should be updated
     */
    public function transform(array $packages)
    {
        static $printed = false;
        if (!$printed) {
            $printed = true;
            $this->log(self::PHABEL, false);
        }
        $this->log("Creating plugin graph...");
        $byName = [];
        foreach ($packages as $package) {
            [$name, $target] = $this->extractTarget($package['name']);
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
                    [$subName] = $this->extractTarget($subName);
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
        $graph = $graph->flatten();
        $traverser = new Traverser($graph->getPlugins());
        $this->requires = $graph->getPackages();
        if (!$this->processedRequires()) {
            $phabelReturn = false;
            if (!\is_bool($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $this->log("Applying transforms...");
        foreach ($byName as $name => $package) {
            $traverser->setPackage($name);
            $it = new \RecursiveDirectoryIterator("vendor/" . $package['name']);
            foreach (new \RecursiveIteratorIterator($it) as $file) {
                if ($file->isFile() && $file->getExtension() == 'php') {
                    $this->io->debug("Transforming " . $file->getRealPath());
                    $traverser->traverse($file->getRealPath(), $file->getRealPath());
                }
            }
        }
        $this->log("Done!");
        $phabelReturn = true;
        if (!\is_bool($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
