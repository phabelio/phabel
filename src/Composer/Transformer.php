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

use Composer\Semver\VersionParser;
use Phabel\EventHandler;
use Phabel\PluginGraph\Graph;
use Phabel\Target\Php;
use Phabel\Traverser;
use ReflectionClass;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class Transformer extends EventHandler
{
    const PHABEL = "
<bold>＊＊＊＊＊＊＊＊＊</>
<bold>＊</bold><phabel> Ｐｈａｂｅｌ </><bold>＊</bold>
<bold>＊＊＊＊＊＊＊＊＊</>

<phabel>PHP transpiler - Write and deploy modern PHP 8 code, today: https://phabel.io</phabel>
";
    const HEADER = 'phabel/transpiler';
    const SEPARATOR = '/';
    /**
     * IO interface.
     */
    private IOInterface $io;
    /**
     * IO interface.
     */
    private OutputFormatter $outputFormatter;

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
     * Instance.
     */
    private static self $instance;
    /**
     * Get singleton.
     *
     * @return self
     */
    public static function getInstance(IOInterface $io): self
    {
        self::$instance ??= new self($io);
        return self::$instance;
    }
    /**
     * Constructor.
     *
     * @param IOInterface $io
     */
    private function __construct(IOInterface $io)
    {
        $this->io = $io;
        $this->versionParser = new VersionParser;

        $this->outputFormatter = new OutputFormatter(true, [
            'bold' => new OutputFormatterStyle('white', 'default', ['bold']),
            'phabel' => new OutputFormatterStyle('blue', 'default', ['bold'])
        ]);
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
        $blue = $this->outputFormatter->format("<phabel>$text</phabel>");
        $this->io->writeError($blue, $newline, $verbosity);
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
            $this->log(self::PHABEL);
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
        $myTarget = Php::normalizeVersion($config['target'] ?? Php::DEFAULT_TARGET);
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
                $this->log("Skipping ".$package->getName()."=$newName", IOInterface::VERY_VERBOSE);
                return;
            }
            $myTarget = $target;
        } else {
            $myTarget = Php::normalizeVersion($myTarget);
            $myTarget = \min($myTarget, $target);
        }

        $this->log("Applying ".$package->getName()."=$newName", IOInterface::VERY_VERBOSE);

        $this->processed = true;
        $this->processedRequires = $this->requires;
        $requires = $this->requires;
        foreach ($config['require'] ?? [] as $name => $constraint) {
            $requires[$this->injectTarget($name, $myTarget)] = $constraint;
        }

        if ($newName !== $package->getName() && \method_exists($package, 'setProvides')) {
            $package->setProvides(\array_merge(
                $package->getProvides(),
                [$package->getName() => new Link(
                    $newName,
                    $package->getName(),
                    new ComposerConstraint('=', $package->getVersion()),
                    Link::TYPE_PROVIDE,
                    $package->getVersion()
                )]
            ));
        }

        $base = new ReflectionClass(BasePackage::class);
        $method = $base->getMethod('__construct');
        $method->invokeArgs($package, [$newName]);

        $this->processRequires(
            $package,
            $myTarget,
            $requires,
            $havePhabel,
        );
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
                    $links[$name]= new Link(
                        $package->getName(),
                        $link->getTarget(),
                        $constraint,
                        $link->getDescription(),
                        $constraint->getPrettyString()
                    );
                } else {
                    $links[$name] = $link;
                }
                continue;
            }
            $links [$name]= new Link(
                $package->getName(),
                $havePhabel ? $link->getTarget() : $this->injectTarget($link->getTarget(), $target),
                $link->getConstraint(),
                $link->getDescription(),
                $link->getPrettyConstraint()
            );
        }
        foreach ($requires as $name => $version) {
            $links[$name] = new Link(
                $package->getName(),
                $name,
                $this->versionParser->parseConstraints($version),
                Link::TYPE_REQUIRE,
                $version
            );
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
        if (\str_starts_with($package, self::HEADER)) {
            [$version, $package] = \explode(self::SEPARATOR, \substr($package, \strlen(self::HEADER)), 2);
            return [$package, $version];
        }
        return [$package, Php::TARGET_IGNORE];
    }

    /**
     * Transform dependencies.
     *
     * @param array $packages
     * @return bool Whether no more packages should be updated
     */
    public function transform(array $packages): bool
    {
        $enabled = \gc_enabled();
        \gc_enable();

        $this->log("Creating plugin graph...", IOInterface::VERBOSE);
        $byName = [];
        foreach ($packages as $package) {
            [$name, $target] = $this->extractTarget($package['name']);
            if ($target === Php::TARGET_IGNORE) {
                continue;
            }
            $package['phabelTarget'] = (int) $target;
            $package['phabelConfig'] = [$package['extra']['phabel'] ?? []];
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

        $graph = new Graph;
        foreach ($byName as $name => $package) {
            $ctx = $graph->getPackageContext();
            $ctx->addPackage($name);
            $target = ['target' => $package['phabelTarget']];
            foreach ($package['phabelConfig'] as $config) {
                $graph->addPlugin(Php::class, $config + $target, $ctx);
            }
        }

        $traverser = new Traverser($this);
        $traverser->setPluginGraph($graph);
        unset($graph);

        $this->requires = $traverser->getGraph()->getPackages();
        if (!$this->processedRequires()) {
            if (!$enabled) {
                unset($traverser);
                while (\gc_collect_cycles());
                \gc_disable();
            }
            return false;
        }

        $this->banner();

        $traverser
            ->setInput('vendor')
            ->setOutput('vendor')
            ->setComposer(function (string $rel): string {
                [$package] = $this->extractTarget(\str_replace('\\', '/', $rel));
                return \implode('/', \array_slice(\explode('/', $package), 0, 2));
            })
            ->run();

        if (!$enabled) {
            unset($traverser);
            while (\gc_collect_cycles());
            \gc_disable();
        }
        return true;
    }

    public function onBeginPluginGraphResolution(): void
    {
        $this->log("Plugin graph resolution in progress...", IOInterface::VERY_VERBOSE);
    }
    public function onEndPluginGraphResolution(): void
    {
        $this->log("Finished plugin graph resolution!", IOInterface::VERY_VERBOSE);
    }

    private ?ProgressBar $progress = null;
    private int $count = 0;

    public function onBeginDirectoryTraversal(int $total): void
    {
        if ($this->doProgress
            && $this->io instanceof ConsoleIO
            && !\getenv('CI')
            && !$this->io->isDebug()
            && !$this->progress
        ) {
            $this->progress = $this->io->getProgressBar($total);
            $this->progress->setFormat($this->outputFormatter->format('<phabel>%message% <bold>%percent:3s%%</bold></phabel> (%current%/%max%)'));
        }
        if (!$this->count) {
            $message = 'Transpilation in progress...';
        } else {
            $secondary = $this->count === 1 ? 'secondary' : 'further';
            $message = "Applying $secondary transforms...";
        }
        $this->count++;
        if ($this->progress) {
            $this->progress->setMessage($message);
            $this->progress->clear();
            $this->progress->start();
        } else {
            $this->log($message, IOInterface::VERBOSE);
        }
    }
    public function onEndAstTraversal(string $file, int $iterations): void
    {
        $this->progress?->advance();
        $this->log("Transpiled $file in $iterations iterations!", IOInterface::VERBOSE);
    }
    public function onEndDirectoryTraversal(): void
    {
        $this->progress?->finish();
        $this->log("");
    }
    public function onEnd(): void
    {
        $this->log("Done!");
        $this->started = false;
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
