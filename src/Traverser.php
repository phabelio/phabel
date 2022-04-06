<?php

namespace Phabel;

use Amp\Parallel\Worker\DefaultPool;
use Amp\Promise;
use Phabel\Composer\Transformer;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\PluginGraph\Graph;
use Phabel\PluginGraph\ResolvedGraph;
use Phabel\Target\Php;
use Phabel\Tasks\Init;
use Phabel\Tasks\Run;
use Phabel\Tasks\Shutdown;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP as PHPReport;
use SplFileInfo;
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
    private ResolvedGraph $graph;
    /**
     * Parser instance.
     */
    private Parser $parser;
    /**
     * Printer instance.
     */
    private Standard $printer;
    /**
     * Plugin queue for specific package.
     *
     * @var SplQueue<SplQueue<PluginInterface>>|null
     */
    private ?SplQueue $packageQueue = null;
    /**
     * Event handler.
     *
     * @var EventHandlerInterface|null
     */
    private ?EventHandlerInterface $eventHandler = null;
    /**
     * Input directory.
     *
     * @var string
     */
    private string $input = '';
    /**
     * Output directory.
     *
     * @var string
     */
    private string $output = '';
    /**
     * Package paths.
     *
     * @var array<string, string>
     */
    private array $packagePaths = [];
    /**
     * Composer paths.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private array $composerPaths = [];
    /**
     * Composer vendor directory.
     *
     * @var string
     */
    private string $composerVendor;
    /**
     * Files to work on.
     *
     * @var list<string>
     */
    private ?array $files = null;
    /**
     * Coverage path.
     *
     * @var string
     */
    private string $coverage = '';
    /**
     * Current input file.
     */
    private string $inputFile = '';
    /**
     * Current output file.
     */
    private string $outputFile = '';

    /**
     * Number of times we traversed directories.
     *
     * @var integer
     */
    private int $count = 0;
    /**
     * Generate traverser from basic plugin instances.
     *
     * @param Plugin ...$plugin Plugins
     *
     * @return self
     */
    public static function fromPlugin(Plugin ...$plugin): self
    {
        $queue = new SplQueue;
        foreach ($plugin as $p) {
            $queue->enqueue($p);
        }
        $final = new SplQueue;
        $final->enqueue($queue);
        $res = new self();
        $res->graph = new ResolvedGraph($final);
        return $res;
    }

    /**
     * Constructor.
     *
     * @param ?EventHandlerInterface $EventHandlerInterface Event handler
     */
    public function __construct(?EventHandlerInterface $eventHandler = null)
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
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
    public function setPlugins(array $plugins): self
    {
        $this->eventHandler?->onBeginPluginGraphResolution();

        $graph = new Graph;
        foreach ($plugins as $plugin => $config) {
            $graph->addPlugin($plugin, $config, $graph->getPackageContext());
        }

        $this->graph = $graph->flatten();

        $this->eventHandler?->onEndPluginGraphResolution();

        return $this;
    }
    /**
     * Set plugin graph.
     *
     * @param Graph $graph
     *
     * @return self
     */
    public function setPluginGraph(Graph $graph): self
    {
        $this->eventHandler?->onBeginPluginGraphResolution();

        $this->graph = $graph->flatten();

        $this->eventHandler?->onEndPluginGraphResolution();

        return $this;
    }

    /**
     * Get resolved plugin graph.
     *
     * @return ResolvedGraph
     */
    public function getGraph(): ResolvedGraph
    {
        return $this->graph;
    }

    /**
     * Set resolved plugin graph.
     *
     * @param ResolvedGraph $graph Resolved graph
     *
     * @return self
     */
    public function setGraph(ResolvedGraph $graph): self
    {
        $this->graph = $graph;

        return $this;
    }

    /**
     * Set input path.
     *
     * @param string $input
     * @return self
     */
    public function setInput(string $input): self
    {
        if (!\file_exists($input)) {
            throw new \RuntimeException("File {$input} does not exist!");
        }

        $this->input = $input;
        return $this;
    }
    /**
     * Set output path.
     *
     * @param string $output
     * @return self
     */
    public function setOutput(string $output): self
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Set composer paths.
     *
     * @param array<string, array{0: string, 1: string}> $paths
     *
     * @return self
     */
    public function setComposerPaths(array $paths): self
    {
        $this->composerPaths = $paths;
        $this->composerVendor = \str_replace('\\', '/', \realpath(\getcwd().'/vendor')).'/';
        $this->packagePaths = [];
        foreach ($paths as $package => [$old, $new]) {
            $this->packagePaths[$old] = $package;
            $this->packagePaths[$new] = $package;
        }

        return $this;
    }

    public function getPackageName(string $path): string
    {
        if (\str_starts_with($path, $this->composerVendor)) {
            [$package] = Transformer::extractTarget(\substr($path, \strlen($this->composerVendor)));
            $result = \implode('/', \array_slice(\explode('/', $package, 3), 0, 2));
            return $result;
        }
        $orig = $path;
        $last = '';
        while (($path = \dirname($path)) !== $last) {
            $last = $path;
            if (isset($this->packagePaths[$path])) {
                return $this->packagePaths[$path];
            }
        }
        throw new RuntimeException("Could not find package for path $orig!");
    }
    /**
     * Set coverage path.
     *
     * @param string $coverage
     * @return self
     */
    public function setCoverage(string $coverage): self
    {
        $this->coverage = $coverage;
        return $this;
    }
    /**
     * Start code coverage.
     *
     * @param string $coveragePath Coverage path
     *
     * @return ?object
     */
    public static function startCoverage(string $coveragePath): ?object
    {
        if (!$coveragePath || !\class_exists(CodeCoverage::class)) {
            return null;
        }
        try {
            $filter = new Filter;
            $filter->includeDirectory(\realpath(__DIR__.'/../src'));

            $coverage = new CodeCoverage(
                (new Selector)->forLineCoverage($filter),
                $filter
            );
            $coverage->start('phabel');

            return new class($coverage, $coveragePath) {
                private string $coveragePath;
                private CodeCoverage $coverage;
                public function __construct(CodeCoverage $coverage, string $coveragePath)
                {
                    $this->coverage = $coverage;
                    $this->coveragePath = $coveragePath;
                }
                public function __destruct()
                {
                    $this->coverage->stop();
                    if (\file_exists($this->coveragePath)) {
                        $this->coverage->merge(require $this->coveragePath);
                    }
                    (new PHPReport)->process($this->coverage, $this->coveragePath);
                }
            };
        } catch (\Throwable $e) {
        }
        return null;
    }
    /**
     * Prepare list of files to work on.
     *
     * @return void
     */
    private function prepareFiles(): void
    {
        if ($this->files === null) {
            if (isset($this->input) && \is_dir($this->input)) {
                if (!\file_exists($this->output)) {
                    \mkdir($this->output, 0777, true);
                }
                $this->output = \realpath($this->output);
                $this->input = \realpath($this->input);
                $this->files = [];

                Tools::traverseCopy($this->input, $this->output, function (SplFileInfo $f, string $input, string $output): bool {
                    if ($f->getExtension() === 'php') {
                        $this->files[\str_replace('\\', '/', $input)] = \str_replace('\\', '/', $output);
                        return true;
                    }
                    return false;
                });
            } elseif ($this->composerPaths) {
                $autoload = $this->composerVendor.'autoload.php';
                $composerDir = [$this->composerVendor.'composer/'];
                foreach (Php::VERSIONS as $target) {
                    $composerDir []= $this->composerVendor.\substr(Transformer::injectTarget('composer/pkg', $target), 0, -3);
                }
                $cb = function (SplFileInfo $f, string $input, string $output) use ($autoload, $composerDir): bool {
                    if ($f->getExtension() !== 'php') {
                        return false;
                    }
                    $real = \str_replace('\\', '/', $input);
                    if ($real === $autoload) {
                        return false;
                    }
                    foreach ($composerDir as $dir) {
                        if (\str_starts_with($real, $dir)) {
                            return false;
                        }
                    }
                    $this->files[$real] = \str_replace('\\', '/', $output);
                    return true;
                };
                Tools::traverseCopy($this->composerVendor, $this->composerVendor, $cb);
                foreach ($this->composerPaths as [$old, $new]) {
                    Tools::traverseCopy($old, $new, $cb);
                }
            }
        }
    }
    /**
     * Run phabel asynchronously.
     *
     * @return array
     */
    public function runAsync(int $threads = -1): array
    {
        return wait($this->runAsyncPromise($threads));
    }
    /**
     * Run phabel asynchronously.
     *
     * @return Promise<array>
     */
    public function runAsyncPromise(int $threads = -1): Promise
    {
        if (!\interface_exists(Promise::class)) {
            throw new Exception("amphp/parallel must be installed to parallelize transforms!");
        }
        $this->eventHandler?->onStart();
        $this->prepareFiles();
        if ($threads === -1) {
            $threads = Tools::getCpuCount();
        }
        $coverages = [];
        return call(function () use (&$coverages, $threads) {
            $packages = [];
            $first = !$this->count++;

            $count = 0;
            $promises = [];
            $classStorage = null;

            $pool = new DefaultPool($threads);
            $promises = [];
            for ($x = 0; $x < $threads; $x++) {
                $promises []= $pool->enqueue(new Init($this->graph));
            }
            yield $promises;
            $packages = $this->graph->getPackages();
            unset($this->graph);

            $this->eventHandler?->onBeginDirectoryTraversal(\count($this->files), $threads);

            $promises = [];
            foreach ($this->files as $input => $output) {
                $promise = call(function () use ($pool, $input, $output, $count, $first, &$promises, &$coverages) {
                    $this->eventHandler?->onBeginAstTraversal($input);
                    $package = null;
                    if ($this->composerPaths) {
                        $package = $this->getPackageName($output);
                    }
                    $res = yield $pool->enqueue(
                        new Run(
                            $input,
                            $output,
                            $package,
                            $this->coverage ? "{$this->coverage}$count.php" : ''
                        )
                    );
                    if ($this->coverage) {
                        $coverages []= "{$this->coverage}$count.php";
                    }
                    if ($res instanceof ExceptionWrapper) {
                        $res = $res->getException();
                        if (!($first && \str_contains($res->getMessage(), ' while parsing '))) {
                            throw $res;
                        }
                        if (\realpath($input) !== \realpath($output)) {
                            \copy($input, $output);
                        }
                        $this->eventHandler?->onEndAstTraversal($input, $res);
                    }
                    \chmod($output, \fileperms($input));
                    unset($promises[$count]);
                });
                $promises[$count] = $promise;
                $count++;
            }
            yield $promises;

            $this->eventHandler?->onEndDirectoryTraversal();
            $this->eventHandler?->onBeginClassGraphMerge($threads);

            $promises = [];
            /** @var ClassStoragePlugin|null */
            $classStorage = null;
            for ($x = 0; $x < $threads; $x++) {
                $promises []= call(function () use ($pool, &$classStorage) {
                    /** @var ClassStoragePlugin */
                    $newClassStorage = yield $pool->enqueue(new Shutdown());
                    if (!$classStorage) {
                        $classStorage = $newClassStorage;
                    } else {
                        $classStorage->merge($newClassStorage);
                    }
                    $this->eventHandler?->onClassGraphMerged();
                });
            }
            yield $promises;
            $this->eventHandler?->onEndClassGraphMerge();

            yield $pool->shutdown();
            unset($pool);

            if ($classStorage) {
                [$plugins, $this->files] = $classStorage->finish($this->iteration);
                unset($classStorage);
                if ($plugins && $this->files) {
                    $this->input = $this->output;
                    $this->composerPaths = [];
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
                    $coverage = include $file;
                    \unlink($file);
                    continue;
                }
                $coverage->merge(include $file);
                \unlink($file);
            }
            if ($coverage) {
                (new PHPReport)->process($coverage, $this->coverage);
            }

            $this->eventHandler?->onEnd();
            return $packages;
        });
    }

    private int $iteration = 0;
    /**
     * Run phabel.
     *
     * @return array<string, string>
     */
    public function run(int $threads = 1): array
    {
        $this->iteration++;
        try {
            if ($threads > 1 || $threads === -1) {
                return $this->runAsync($threads);
            }
            \set_error_handler(
                function (int $errno = 0, string $errstr = '', string $errfile = '', int $errline = -1): bool {
                // If error is suppressed with @, don't throw an exception
                if (\error_reporting() === 0) {
                    return false;
                }
                throw new Exception($errstr, $errno, null, $errfile, $errline);
            }
            );

            $packages = [];

            $this->eventHandler?->onStart();
            $this->prepareFiles();
            while (true) {
                $this->runInternal();
                $packages += $this->graph->getPackages();
                $classStorage = $this->graph->getClassStorage();
                if (!$classStorage) {
                    break;
                }
                [$plugins, $this->files] = $classStorage->finish($this->iteration);
                \var_dump($plugins);
                unset($classStorage);
                if (!$plugins || !$this->files) {
                    break;
                }
                $this->input = $this->output;
                $this->composerPaths = [];
                $this->setPlugins($plugins);
            }
            $this->eventHandler?->onEnd();

            \restore_error_handler();

            return $packages;
        } finally {
            $this->iteration--;
        }
    }
    /**
     * Run phabel (internal function).
     *
     * @internal
     */
    public function runInternal(): void
    {
        $_ = self::startCoverage($this->coverage);
        $first = !$this->count++;
        $this->packageQueue = null;

        if (\is_file($this->input)) {
            $this->traverse(\realpath($this->input), \realpath($this->output) ?: $this->output);
            return;
        }

        $this->eventHandler->onBeginDirectoryTraversal(\count($this->files), 1);
        foreach ($this->files as $input => $output) {
            $_ = self::startCoverage($this->coverage);
            if ($this->composerPaths) {
                $this->setPackage($this->getPackageName($output));
            } else {
                $this->packageQueue = null;
            }
            try {
                $it = $this->traverse($input, $output);
            } catch (\Throwable $e) {
                if (!($first && $e instanceof Exception && \str_contains($e->getMessage(), ' while parsing '))) {
                    throw $e;
                }
                if (\realpath($input) !== \realpath($output)) {
                    \copy($input, $output);
                }
                $this->eventHandler?->onEndAstTraversal($input, $e);
            }
            \chmod($output, \fileperms($input));
        }
        $this->eventHandler?->onEndDirectoryTraversal();
    }

    /**
     * Set package name.
     *
     * @param ?string $package Package name
     *
     * @return void
     */
    public function setPackage(?string $package): void
    {
        /** @var SplQueue<SplQueue<PluginInterface>> */
        if (!$package) {
            $this->packageQueue = null;
            return;
        }
        $this->packageQueue = new SplQueue;
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue;
        foreach ($this->graph->getPlugins() as $queue) {
            if ($newQueue->count()) {
                $this->packageQueue->enqueue($newQueue);
                /** @var SplQueue<PluginInterface> */
                $newQueue = new SplQueue;
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
    public function traverse(string $input, string $output): int
    {
        $this->eventHandler?->onBeginAstTraversal($input);

        /** @var SplQueue<SplQueue<PluginInterface>> */
        $reducedQueue = new SplQueue;
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue;
        foreach ($this->packageQueue ?? $this->graph->getPlugins() as $queue) {
            if ($newQueue->count()) {
                $reducedQueue->enqueue($newQueue);
                /** @var SplQueue<PluginInterface> */
                $newQueue = new SplQueue;
            }
            /** @var Plugin */
            foreach ($queue as $plugin) {
                if ($plugin->shouldRunFile($input)) {
                    $newQueue->enqueue($plugin);
                }
            }
        }
        if ($newQueue->count()) {
            $reducedQueue->enqueue($newQueue);
        } elseif (!$reducedQueue->count()) {
            if (\realpath($input) !== \realpath($output)) {
                \copy($input, $output);
            }
            $this->eventHandler?->onEndAstTraversal($input, 0);
            return 0;
        }

        try {
            $ast = new RootNode($this->parser->parse(\file_get_contents($input)) ?? []);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $message .= " while parsing ";
            $message .= $input;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        }

        $this->inputFile = $input;
        $this->outputFile = $output;
        [$it, $result] = $this->traverseAstInternal($ast, $reducedQueue);
        \file_put_contents($output, $result);

        $this->eventHandler?->onEndAstTraversal($input, $it);

        return $it;
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
    public function traverseAst(Node &$node, SplQueue $pluginQueue = null, bool $allowMulti = true): int
    {
        $this->inputFile = '';
        $this->outputFile = '';
        $n = new RootNode([&$node]);
        return $this->traverseAstInternal($n, $pluginQueue, $allowMulti)[0] ?? 0;
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
    private function traverseAstInternal(RootNode &$node, SplQueue $pluginQueue = null, bool $allowMulti = true): ?array
    {
        $it = 0;
        $result = $this->printer->prettyPrintFile($node->stmts);
        do {
            $context = null;
            try {
                foreach ($pluginQueue ?? $this->packageQueue ?? $this->graph->getPlugins() as $queue) {
                    $context = new Context;
                    $context->setInputFile($this->inputFile);
                    $context->setOutputFile($this->outputFile);
                    $context->push($node);
                    $this->traverseNode($node, $queue, $context);
                    /** @var RootNode $node */
                }
                if (!$allowMulti) {
                    return null;
                }
            } catch (\Throwable $e) {
                $message = $e->getMessage();
                $message .= " while processing ";
                $message .= $this->inputFile;
                $message .= ":";
                try {
                    $message .= $context ? $context->getCurrentChild($context->parents[0])->getStartLine() : "-1";
                } catch (\Throwable $e) {
                    $message .= "-1";
                }
                throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
            }
            $oldResult = $result;
            $result = $this->printer->prettyPrintFile($node->stmts);
            $it++;
        } while ($result !== $oldResult);
        return [$it, $result];
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
    private function traverseNode(Node &$node, SplQueue $plugins, Context $context): void
    {
        $context->pushResolve($node);
        foreach ($plugins as $plugin) {
            foreach (PluginCache::enterMethods(\get_class($plugin)) as $type => $methods) {
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
                    /** @var Node|null */
                    $result = $plugin->{$method}($node, $context);
                    if ($result instanceof Node) {
                        if (!$result instanceof $node) {
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
            $subNode = &$node->{$name};
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
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
                    /** @var Node|null */
                    $result = $plugin->{$method}($node, $context);
                    if ($result instanceof Node) {
                        if (!$result instanceof $node) {
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
        while (\gc_collect_cycles());
    }
}
