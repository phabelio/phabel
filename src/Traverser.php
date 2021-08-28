<?php

namespace Phabel;

use Amp\Parallel\Worker\DefaultPool;
use Amp\Promise;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\PluginGraph\Graph;
use Phabel\PluginGraph\ResolvedGraph;
use Phabel\Tasks\Init;
use Phabel\Tasks\Run;
use Phabel\Tasks\Shutdown;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP;
use SplQueue;
use function Amp\call;
use function Amp\Promise\wait;

/**
 * AST traverser.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Traverser
{
    /**
     * Resolved plugin graph.
     *
     * @var ResolvedGraph
     */
    private $graph;
    /**
     * Parser instance.
     */
    private $parser;
    /**
     * Printer instance.
     */
    private $printer;
    /**
     * Plugin queue for specific package.
     *
     * @var SplQueue<SplQueue<PluginInterface>>|null
     */
    private $packageQueue = null;
    /**
     * Event handler.
     *
     * @var EventHandlerInterface|null
     */
    private $eventHandler = null;
    /**
     * Input.
     *
     * @var string
     */
    private $input = '';
    /**
     * Output.
     *
     * @var string
     */
    private $output = '';
    /**
     * File whitelist.
     */
    private $fileWhitelist = null;
    /**
     * Callable to extract package name from path.
     *
     * @var (callable(string): string)|null
     */
    private $composerPackageName = null;
    /**
     * Coverage path.
     *
     * @var string
     */
    private $coverage = '';
    /**
     * Current file.
     */
    private $file = '';
    /**
     * Current input file.
     */
    private $inputFile = '';
    /**
     * Current output file.
     */
    private $outputFile = '';
    /**
     * Number of times we traversed directories.
     *
     * @var integer
     */
    private $count = 0;
    /**
     * Generate traverser from basic plugin instances.
     *
     * @param Plugin ...$plugin Plugins
     *
     * @return self
     */
    public static function fromPlugin(Plugin ...$plugin)
    {
        $queue = new SplQueue();
        foreach ($plugin as $p) {
            $queue->enqueue($p);
        }
        $final = new SplQueue();
        $final->enqueue($queue);
        $res = new self();
        $res->graph = new ResolvedGraph($final);
        $phabelReturn = $res;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Constructor.
     *
     * @param ?EventHandlerInterface $EventHandlerInterface Event handler
     */
    public function __construct($eventHandler = null)
    {
        if (!($eventHandler instanceof EventHandlerInterface || \is_null($eventHandler))) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($eventHandler) must be of type ?EventHandlerInterface, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($eventHandler) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->printer = new Standard();
        $this->eventHandler = $eventHandler;
    }
    /**
     * Set plugin array.
     *
     * @param array $plugins
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return self
     */
    public function setPlugins(array $plugins)
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onBeginPluginGraphResolution();
        $graph = new Graph();
        foreach ($plugins as $plugin => $config) {
            $graph->addPlugin($plugin, $config, $graph->getPackageContext());
        }
        $this->graph = $graph->flatten();
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndPluginGraphResolution();
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set plugin graph.
     *
     * @param Graph $graph
     *
     * @return self
     */
    public function setPluginGraph(Graph $graph)
    {
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onBeginPluginGraphResolution();
        $this->graph = $graph->flatten();
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndPluginGraphResolution();
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get resolved plugin graph.
     *
     * @return ResolvedGraph
     */
    public function getGraph()
    {
        $phabelReturn = $this->graph;
        if (!$phabelReturn instanceof ResolvedGraph) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ResolvedGraph, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set resolved plugin graph.
     *
     * @param ResolvedGraph $graph Resolved graph
     *
     * @return self
     */
    public function setGraph(ResolvedGraph $graph)
    {
        $this->graph = $graph;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set input path.
     *
     * @param string $input
     * @return self
     */
    public function setInput($input)
    {
        if (!\is_string($input)) {
            if (!(\is_string($input) || \is_object($input) && \method_exists($input, '__toString') || (\is_bool($input) || \is_numeric($input)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $input = (string) $input;
        }
        if (!\file_exists($input)) {
            throw new \RuntimeException("File {$input} does not exist!");
        }
        $this->input = $input;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set output path.
     *
     * @param string $output
     * @return self
     */
    public function setOutput($output)
    {
        if (!\is_string($output)) {
            if (!(\is_string($output) || \is_object($output) && \method_exists($output, '__toString') || (\is_bool($output) || \is_numeric($output)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $output = (string) $output;
        }
        $this->output = $output;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set callable to extract composer package name from path.
     *
     * @param callable $composer
     *
     * @return self
     */
    public function setComposer(callable $composer)
    {
        $this->composerPackageName = $composer;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set coverage path.
     *
     * @param string $coverage
     * @return self
     */
    public function setCoverage($coverage)
    {
        if (!\is_string($coverage)) {
            if (!(\is_string($coverage) || \is_object($coverage) && \method_exists($coverage, '__toString') || (\is_bool($coverage) || \is_numeric($coverage)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($coverage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coverage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $coverage = (string) $coverage;
        }
        $this->coverage = $coverage;
        $phabelReturn = $this;
        if (!$phabelReturn instanceof self) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ' . self::class . ', ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Start code coverage.
     *
     * @param string $coveragePath Coverage path
     *
     * @return ?object
     */
    public static function startCoverage($coveragePath)
    {
        if (!\is_string($coveragePath)) {
            if (!(\is_string($coveragePath) || \is_object($coveragePath) && \method_exists($coveragePath, '__toString') || (\is_bool($coveragePath) || \is_numeric($coveragePath)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($coveragePath) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coveragePath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $coveragePath = (string) $coveragePath;
        }
        if (!$coveragePath || !\class_exists(CodeCoverage::class)) {
            $phabelReturn = null;
            if (!(\is_object($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        try {
            $filter = new Filter();
            $filter->includeDirectory(\realpath(__DIR__ . '/../src'));
            $coverage = new CodeCoverage((new Selector())->forLineCoverage($filter), $filter);
            $coverage->start('phabel');
            $phabelReturn = new PhabelAnonymousClass063d10f883fe83b0c12eba721edc03b9cc7383d4550ab4363c290322abe45bb50($coverage, $coveragePath);
            if (!(\is_object($phabelReturn) || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        } catch (\Exception $e) {
        } catch (\Error $e) {
        }
        $phabelReturn = null;
        if (!(\is_object($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?object, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Run phabel asynchronously.
     *
     * @return array
     */
    public function runAsync($threads = -1)
    {
        if (!\is_int($threads)) {
            if (!(\is_bool($threads) || \is_numeric($threads))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($threads) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($threads) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $threads = (int) $threads;
        }
        $phabelReturn = wait($this->runAsyncPromise($threads));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Run phabel asynchronously.
     *
     * @return Promise<array>
     */
    public function runAsyncPromise($threads = -1)
    {
        if (!\is_int($threads)) {
            if (!(\is_bool($threads) || \is_numeric($threads))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($threads) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($threads) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $threads = (int) $threads;
        }
        if (!\interface_exists(Promise::class)) {
            throw new Exception("amphp/parallel must be installed to parallelize transforms!");
        }
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onStart();
        if ($threads === -1) {
            $threads = Tools::getCpuCount();
        }
        $coverages = [];
        $phabelReturn = call(function () use (&$coverages, $threads) {
            $packages = [];
            $first = !$this->count++;
            if (!\file_exists($this->output)) {
                \mkdir($this->output, 0777, true);
            }
            $output = \realpath($this->output);
            $count = 0;
            $promises = [];
            $classStorage = null;
            $pool = new DefaultPool($threads);
            $promises = [];
            for ($x = 0; $x < $threads; $x++) {
                $promises[] = $pool->enqueue(new Init($this->graph));
            }
            (yield $promises);
            $packages = $this->graph->getPackages();
            unset($this->graph);
            $it = new \RecursiveDirectoryIterator($this->input, \RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
            if ($this->eventHandler) {
                $this->eventHandler->onBeginDirectoryTraversal($this->fileWhitelist ? \count($this->fileWhitelist) : \iterator_count($ri), $threads);
            }
            $promises = [];
            /** @var \SplFileInfo $file */
            foreach ($ri as $file) {
                $rel = $ri->getSubPathname();
                if ($this->fileWhitelist && !isset($this->fileWhitelist[$rel])) {
                    continue;
                }
                $targetPath = $output . DIRECTORY_SEPARATOR . $rel;
                if ($file->isDir()) {
                    if (!\file_exists($targetPath)) {
                        \mkdir($targetPath, 0777, true);
                    }
                } elseif ($file->isFile()) {
                    if ($file->getExtension() == 'php') {
                        $promise = call(function () use ($pool, $file, $rel, $targetPath, $count, $first, &$promises, &$coverages) {
                            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onBeginAstTraversal($file->getRealPath());
                            $package = null;
                            if ($this->composerPackageName) {
                                $phabel_c507848c74a74245 = $this->composerPackageName;
                                $package = $phabel_c507848c74a74245($rel);
                            }
                            $res = (yield $pool->enqueue(new Run($rel, $file->getRealPath(), $targetPath, $package, $this->coverage ? "{$this->coverage}{$count}.php" : '')));
                            if ($this->coverage) {
                                $coverages[] = "{$this->coverage}{$count}.php";
                            }
                            if ($res instanceof ExceptionWrapper) {
                                $res = $res->getException();
                                if (!($first && \str_contains($res->getMessage(), ' while parsing '))) {
                                    throw $res;
                                }
                                if (\realpath($targetPath) !== $file->getRealPath()) {
                                    \copy($file->getRealPath(), $targetPath);
                                }
                            }
                            \chmod($targetPath, \fileperms($file->getRealPath()));
                            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndAstTraversal($file->getRealPath(), $res);
                            unset($promises[$count]);
                        });
                        $promises[$count] = $promise;
                        $count++;
                    } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                        \copy($file->getRealPath(), $targetPath);
                        \chmod($targetPath, \fileperms($file->getRealPath()));
                    }
                }
            }
            (yield $promises);
            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndDirectoryTraversal();
            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onBeginClassGraphMerge($threads);
            $promises = [];
            /** @var ClassStoragePlugin|null */
            $classStorage = null;
            for ($x = 0; $x < $threads; $x++) {
                $promises[] = call(function () use ($pool, &$classStorage) {
                    /** @var ClassStoragePlugin */
                    $newClassStorage = (yield $pool->enqueue(new Shutdown()));
                    if (!$classStorage) {
                        $classStorage = $newClassStorage;
                    } else {
                        $classStorage->merge($newClassStorage);
                    }
                    \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onClassGraphMerged();
                });
            }
            (yield $promises);
            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndClassGraphMerge();
            (yield $pool->shutdown());
            unset($pool);
            if ($classStorage) {
                list($plugins, $files) = $classStorage->finish();
                unset($classStorage);
                if ($plugins && $files) {
                    $this->input = $this->output;
                    $this->fileWhitelist = $files;
                    $this->composerPackageName = null;
                    $this->setPlugins($plugins);
                    return $this->run();
                }
            }
            /** @var CodeCoverage|null $coverage */
            $coverage = null;
            foreach ($coverages as $file) {
                if (!\file_exists($file)) {
                    continue;
                }
                if (!$coverage) {
                    /** @var CodeCoverage $coverage */
                    $coverage = (include $file);
                    \unlink($file);
                    continue;
                }
                $coverage->merge(include $file);
                \unlink($file);
            }
            if ($coverage) {
                (new PHP())->process($coverage, $this->coverage);
            }
            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEnd();
            return $packages;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Run phabel.
     *
     * @return array<string, string>
     */
    public function run($threads = 1)
    {
        if (!\is_int($threads)) {
            if (!(\is_bool($threads) || \is_numeric($threads))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($threads) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($threads) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $threads = (int) $threads;
        }
        if ($threads > 1 || $threads === -1) {
            $phabelReturn = $this->runAsync($threads);
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        \set_error_handler(function ($errno = 0, $errstr = '', $errfile = '', $errline = -1) {
            if (!\is_int($errno)) {
                if (!(\is_bool($errno) || \is_numeric($errno))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($errno) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errno) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errno = (int) $errno;
            }
            if (!\is_string($errstr)) {
                if (!(\is_string($errstr) || \is_object($errstr) && \method_exists($errstr, '__toString') || (\is_bool($errstr) || \is_numeric($errstr)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($errstr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errstr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errstr = (string) $errstr;
            }
            if (!\is_string($errfile)) {
                if (!(\is_string($errfile) || \is_object($errfile) && \method_exists($errfile, '__toString') || (\is_bool($errfile) || \is_numeric($errfile)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #3 ($errfile) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errfile) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errfile = (string) $errfile;
            }
            if (!\is_int($errline)) {
                if (!(\is_bool($errline) || \is_numeric($errline))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #4 ($errline) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $errline = (int) $errline;
            }
            // If error is suppressed with @, don't throw an exception
            if (\error_reporting() === 0) {
                $phabelReturn = false;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    $phabelReturn = (bool) $phabelReturn;
                }
                return $phabelReturn;
            }
            throw new Exception($errstr, $errno, null, $errfile, $errline);
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        });
        $packages = [];
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onStart();
        while (true) {
            $this->runInternal();
            $packages += $this->graph->getPackages();
            $classStorage = $this->graph->getClassStorage();
            if (!$classStorage) {
                break;
            }
            list($plugins, $files) = $classStorage->finish();
            unset($classStorage);
            if (!$plugins || !$files) {
                break;
            }
            $this->input = $this->output;
            $this->fileWhitelist = $files;
            $this->composerPackageName = null;
            $this->setPlugins($plugins);
        }
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEnd();
        \restore_error_handler();
        $phabelReturn = $packages;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Run phabel (internal function).
     *
     * @internal
     */
    public function runInternal()
    {
        $_ = self::startCoverage($this->coverage);
        $first = !$this->count++;
        $this->packageQueue = null;
        if (\is_file($this->input)) {
            $this->traverse(\basename($this->input), \realpath($this->input), \realpath($this->output) ?: $this->output);
            return;
        }
        if (!\file_exists($this->output)) {
            \mkdir($this->output, 0777, true);
        }
        $this->output = \realpath($this->output);
        $it = new \RecursiveDirectoryIterator($this->input, \RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
        if ($this->eventHandler) {
            $this->eventHandler->onBeginDirectoryTraversal($this->fileWhitelist ? \count($this->fileWhitelist) : \iterator_count($ri), 1);
        }
        /** @var \SplFileInfo $file */
        foreach ($ri as $file) {
            $rel = $ri->getSubPathname();
            if ($this->fileWhitelist && !isset($this->fileWhitelist[$rel])) {
                continue;
            }
            $targetPath = $this->output . DIRECTORY_SEPARATOR . $rel;
            if ($file->isDir()) {
                if (!\file_exists($targetPath)) {
                    \mkdir($targetPath, 0777, true);
                }
            } elseif ($file->isFile()) {
                if ($file->getExtension() == 'php') {
                    $_ = self::startCoverage($this->coverage);
                    if ($this->composerPackageName) {
                        $phabel_b1a55408243b319b = $this->composerPackageName;
                        $this->setPackage($phabel_b1a55408243b319b($rel));
                    } else {
                        $this->packageQueue = null;
                    }
                    try {
                        $it = $this->traverse($rel, $file->getRealPath(), $targetPath);
                    } catch (\Exception $e) {
                        if (!($first && $e instanceof Exception && \str_contains($e->getMessage(), ' while parsing '))) {
                            throw $e;
                        }
                        if (\realpath($targetPath) !== $file->getRealPath()) {
                            \copy($file->getRealPath(), $targetPath);
                        }
                        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndAstTraversal($file->getRealPath(), $e);
                    } catch (\Error $e) {
                        if (!($first && $e instanceof Exception && \str_contains($e->getMessage(), ' while parsing '))) {
                            throw $e;
                        }
                        if (\realpath($targetPath) !== $file->getRealPath()) {
                            \copy($file->getRealPath(), $targetPath);
                        }
                        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndAstTraversal($file->getRealPath(), $e);
                    }
                } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                    \copy($file->getRealPath(), $targetPath);
                }
                \chmod($targetPath, \fileperms($file->getRealPath()));
            }
        }
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndDirectoryTraversal();
    }
    /**
     * Set package name.
     *
     * @param ?string $package Package name
     *
     * @return void
     */
    public function setPackage($package)
    {
        if (!\is_null($package)) {
            if (!\is_string($package)) {
                if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type ?string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $package = (string) $package;
            }
        }
        /** @var SplQueue<SplQueue<PluginInterface>> */
        if (!$package) {
            $this->packageQueue = null;
            return;
        }
        $this->packageQueue = new SplQueue();
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue();
        foreach ($this->graph->getPlugins() as $queue) {
            if ($newQueue->count()) {
                $this->packageQueue->enqueue($newQueue);
                /** @var SplQueue<PluginInterface> */
                $newQueue = new SplQueue();
            }
            /** @var Plugin */
            foreach ($queue as $plugin) {
                if ($plugin->shouldRun($package)) {
                    $newQueue->enqueue($plugin);
                }
            }
        }
        if ($newQueue->count()) {
            $this->packageQueue->enqueue($newQueue);
        }
    }
    /**
     * Traverse AST of file.
     *
     * @param string $file Relative path
     * @param string $input Input file
     * @param string $output Output file
     *
     * @return int
     */
    public function traverse($file, $input, $output)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        if (!\is_string($input)) {
            if (!(\is_string($input) || \is_object($input) && \method_exists($input, '__toString') || (\is_bool($input) || \is_numeric($input)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $input = (string) $input;
        }
        if (!\is_string($output)) {
            if (!(\is_string($output) || \is_object($output) && \method_exists($output, '__toString') || (\is_bool($output) || \is_numeric($output)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $output = (string) $output;
        }
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onBeginAstTraversal($input);
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $reducedQueue = new SplQueue();
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue();
        foreach (isset($this->packageQueue) ? $this->packageQueue : $this->graph->getPlugins() as $queue) {
            if ($newQueue->count()) {
                $reducedQueue->enqueue($newQueue);
                /** @var SplQueue<PluginInterface> */
                $newQueue = new SplQueue();
            }
            /** @var Plugin */
            foreach ($queue as $plugin) {
                if ($plugin->shouldRunFile($file)) {
                    $newQueue->enqueue($plugin);
                }
            }
        }
        if ($newQueue->count()) {
            $reducedQueue->enqueue($newQueue);
        } elseif (!$reducedQueue->count()) {
            \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndAstTraversal($input, 0);
            $phabelReturn = 0;
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        try {
            $ast = new RootNode(null !== ($phabel_6f5fa46f697baa56 = $this->parser->parse(\file_get_contents($input))) ? $phabel_6f5fa46f697baa56 : []);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message .= " while parsing ";
            $message .= $input;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        } catch (\Error $e) {
            $message = $e->getMessage();
            $message .= " while parsing ";
            $message .= $input;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        }
        $this->file = $file;
        $this->inputFile = $input;
        $this->outputFile = $output;
        list($it, $result) = $this->traverseAstInternal($ast, $reducedQueue);
        \file_put_contents($output, $result);
        \Phabel\Plugin\NestedExpressionFixer::returnMe(isset($this->eventHandler) ? $this->eventHandler : \Phabel\Target\Php80\NullSafe\NullSafe::$singleton)->onEndAstTraversal($input, $it);
        $phabelReturn = $it;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Traverse AST.
     *
     * @param Node     $node        Initial node
     * @param SplQueue $pluginQueue Plugin queue (optional)
     * @param bool     $allowMulti  Whether to allow multiple iterations on the plugins
     *
     * @psalm-param SplQueue<SplQueue<PluginInterface>> $pluginQueue
     *
     * @return int
     */
    public function traverseAst(Node &$node, SplQueue $pluginQueue = null, $allowMulti = true)
    {
        if (!\is_bool($allowMulti)) {
            if (!(\is_bool($allowMulti) || \is_numeric($allowMulti) || \is_string($allowMulti))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($allowMulti) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowMulti) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $allowMulti = (bool) $allowMulti;
        }
        $this->file = '';
        $this->inputFile = '';
        $this->outputFile = '';
        $n = new RootNode([&$node]);
        $phabelReturn = null !== ($phabel_99db884988fa8271 = $this->traverseAstInternal($n, $pluginQueue, $allowMulti)) && isset($phabel_99db884988fa8271[0]) ? $phabel_99db884988fa8271[0] : 0;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Traverse AST.
     *
     * @param RootNode &$node       Initial node
     * @param SplQueue $pluginQueue Plugin queue (optional)
     * @param bool     $allowMulti  Whether to allow multiple iterations on the AST
     *
     * @psalm-param SplQueue<SplQueue<PluginInterface>> $pluginQueue
     * @template T as bool
     *
     * @psalm-param T $allowMulti
     *
     * @return array{0: int, 1: string}|null
     * @psalm-return (T is true ? array{0: int, 1: string} : null)
     */
    private function traverseAstInternal(RootNode &$node, SplQueue $pluginQueue = null, $allowMulti = true)
    {
        if (!\is_bool($allowMulti)) {
            if (!(\is_bool($allowMulti) || \is_numeric($allowMulti) || \is_string($allowMulti))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($allowMulti) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowMulti) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $allowMulti = (bool) $allowMulti;
        }
        $it = 0;
        $result = $this->printer->prettyPrintFile($node->stmts);
        do {
            $context = null;
            try {
                foreach (isset($pluginQueue) ? $pluginQueue : (isset($this->packageQueue) ? $this->packageQueue : $this->graph->getPlugins()) as $queue) {
                    $context = new Context();
                    $context->setFile($this->file);
                    $context->setInputFile($this->inputFile);
                    $context->setOutputFile($this->outputFile);
                    $context->push($node);
                    $this->traverseNode($node, $queue, $context);
                    /** @var RootNode $node */
                }
                if (!$allowMulti) {
                    $phabelReturn = null;
                    if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    }
                    return $phabelReturn;
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $message .= " while processing ";
                $message .= $this->file;
                $message .= ":";
                try {
                    $message .= $context ? $context->getCurrentChild($context->parents[0])->getStartLine() : "-1";
                } catch (\Exception $e) {
                    $message .= "-1";
                } catch (\Error $e) {
                    $message .= "-1";
                }
                throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
            } catch (\Error $e) {
                $message = $e->getMessage();
                $message .= " while processing ";
                $message .= $this->file;
                $message .= ":";
                try {
                    $message .= $context ? $context->getCurrentChild($context->parents[0])->getStartLine() : "-1";
                } catch (\Exception $e) {
                    $message .= "-1";
                } catch (\Error $e) {
                    $message .= "-1";
                }
                throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
            }
            $oldResult = $result;
            $result = $this->printer->prettyPrintFile($node->stmts);
            $it++;
        } while ($result !== $oldResult);
        $phabelReturn = [$it, $result];
        if (!(\is_array($phabelReturn) || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Traverse node.
     *
     * @param Node             &$node   Node
     * @param SplQueue<PluginInterface> $plugins Plugins
     * @param Context          $context Context
     *
     * @return void
     */
    private function traverseNode(Node &$node, SplQueue $plugins, Context $context)
    {
        $context->pushResolve($node);
        foreach ($plugins as $plugin) {
            foreach (PluginCache::enterMethods(\get_class($plugin)) as $type => $methods) {
                if (!\Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($node, $type)) {
                    continue;
                }
                foreach ($methods as $method) {
                    /** @var Node|null */
                    $result = $plugin->{$method}($node, $context);
                    if ($result instanceof Node) {
                        if (!\Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($result, $node)) {
                            $node = $result;
                            continue 2;
                        }
                        $node = $result;
                    }
                }
            }
        }
        $context->push($node);
        /** @var string $name */
        foreach ($node->getSubNodeNames() as $name) {
            $node->setAttribute('currentNode', $name);
            /** @var Node[]|Node|mixed */
            $subNode =& $node->{$name};
            if ($subNode instanceof Node) {
                $this->traverseNode($subNode, $plugins, $context);
                continue;
            }
            if (!\is_array($subNode)) {
                continue;
            }
            for ($index = 0; $index < \count($subNode);) {
                $node->setAttribute('currentNodeIndex', $index);
                if ($subNode[$index] instanceof Node) {
                    $this->traverseNode($subNode[$index], $plugins, $context);
                }
                /**
                 * @psalm-suppress MixedOperand
                 * @var int
                 */
                $index = $node->getAttribute('currentNodeIndex') + 1;
            }
            $node->setAttribute('currentNodeIndex', null);
        }
        $context->pop();
        foreach ($plugins as $plugin) {
            foreach (PluginCache::leaveMethods(\get_class($plugin)) as $type => $methods) {
                if (!\Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($node, $type)) {
                    continue;
                }
                foreach ($methods as $method) {
                    /** @var Node|null */
                    $result = $plugin->{$method}($node, $context);
                    if ($result instanceof Node) {
                        if (!\Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($result, $node)) {
                            $node = $result;
                            continue 2;
                        }
                        $node = $result;
                    }
                }
            }
        }
    }
    public function __destruct()
    {
        unset($this->graph);
        while (\gc_collect_cycles()) {
        }
    }
}
if (!\class_exists(PhabelAnonymousClass063d10f883fe83b0c12eba721edc03b9cc7383d4550ab4363c290322abe45bb50::class)) {
    class PhabelAnonymousClass063d10f883fe83b0c12eba721edc03b9cc7383d4550ab4363c290322abe45bb50 implements \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        private $coveragePath;
        private $coverage;
        public function __construct(CodeCoverage $coverage, $coveragePath)
        {
            if (!\is_string($coveragePath)) {
                if (!(\is_string($coveragePath) || \is_object($coveragePath) && \method_exists($coveragePath, '__toString') || (\is_bool($coveragePath) || \is_numeric($coveragePath)))) {
                    throw new \TypeError(__METHOD__ . '(): Argument #2 ($coveragePath) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coveragePath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $coveragePath = (string) $coveragePath;
            }
            $this->coverage = $coverage;
            $this->coveragePath = $coveragePath;
        }
        public function __destruct()
        {
            $this->coverage->stop();
            if (\file_exists($this->coveragePath)) {
                $this->coverage->merge(require $this->coveragePath);
            }
            (new PHP())->process($this->coverage, $this->coveragePath);
        }
        public static function getPhabelOriginalName()
        {
            return 'class@anonymous';
        }
    }
}
