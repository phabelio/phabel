<?php

namespace Phabel;

use Amp\Promise;
use Phabel\PluginGraph\Graph;
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
use function Amp\Parallel\Worker\enqueueCallable;

/**
 * AST traverser.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Traverser
{
    /**
     * Plugin queue.
     *
     * @var SplQueue<SplQueue<PluginInterface>>
     */
    private SplQueue $queue;
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
     * Current file.
     */
    private string $file = '';
    /**
     * Current output file.
     */
    private string $outputFile = '';
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
        return new self($final);
    }
    /**
     * Start code coverage.
     *
     * @param string $coveragePath Coverage path
     *
     * @return ?object
     */
    private static function startCoverage(string $coveragePath): ?object
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
                    (new PHP)->process($this->coverage, $this->coveragePath);
                }
            };
        } catch (\Throwable $e) {
        }
        return null;
    }
    /**
     * Run phabel.
     *
     * @param array $plugins   Plugins
     * @param string $input    Input file/directory
     * @param string $output   Output file/directory
     * @param string $coverage Coverage path
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return array<string, string>
     */
    public static function run(array $plugins, string $input, string $output, string $coverage = ''): array
    {
        [$classStorage, $packages] = self::runInternal($plugins, $input, $output, $coverage);
        if ($classStorage) {
            self::run($classStorage->finish(), $output, $output, $coverage);
        }
        return $packages;
    }
    /**
     * Run phabel.
     *
     * @param array $plugins   Plugins
     * @param string $input    Input file/directory
     * @param string $output   Output file/directory
     * @param string $coverage Coverage path
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return array{0: ?ClassStoragePlugin, 1: array<string, string>}
     */
    private static function runInternal(array $plugins, string $input, string $output, string $coverage = ''): array
    {
        $_ = self::startCoverage($coverage);
        \set_error_handler(
            function (int $errno = 0, string $errstr = '', string $errfile = '', int $errline = -1): bool {
                // If error is suppressed with @, don't throw an exception
                if (\error_reporting() === 0) {
                    return false;
                }
                throw new Exception($errstr, $errno, null, $errfile, $errline);
            }
        );

        $graph = new Graph;
        foreach ($plugins as $plugin => $config) {
            $graph->addPlugin($plugin, $config, $graph->getPackageContext());
        }

        $graph = $graph->flatten();
        $p = new Traverser($graph->getPlugins());

        if (!\file_exists($input)) {
            throw new \RuntimeException("File $input does not exist!");
        }

        if (\is_file($input)) {
            $it = $p->traverse(realpath($input), $output);
            echo("Transformed ".$input." in $it iterations".PHP_EOL);
            return [$graph->getClassStorage(), $graph->getPackages()];
        }

        if (!\file_exists($output)) {
            \mkdir($output, 0777, true);
        }
        $output = realpath($output);

        $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var \SplFileInfo $file */
        foreach ($ri as $file) {
            $targetPath = $output.DIRECTORY_SEPARATOR.$ri->getSubPathname();
            if ($file->isDir()) {
                if (!\file_exists($targetPath)) {
                    \mkdir($targetPath, 0777, true);
                }
            } elseif ($file->isFile()) {
                if ($file->getExtension() == 'php') {
                    $_ = self::startCoverage($coverage);
                    $it = $p->traverse($file->getRealPath(), $targetPath);
                    echo("Transformed ".$file->getRealPath()." in $it iterations".PHP_EOL);
                } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                    \copy($file->getRealPath(), $targetPath);
                }
            }
        }

        return [$graph->getClassStorage(), $graph->getPackages()];
    }

    /**
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     * @param string $prefix Coverage prefix
     * @return Promise<array>
     */
    public static function runAsync(array $plugins, string $input, string $output, string $prefix): Promise
    {
        if (!\interface_exists(Promise::class)) {
            throw new Exception("amphp/parallel must be installed to parallelize transforms!");
        }
        return call(function () use ($plugins, $input, $output, $prefix) {
            $result = [];

            if (!\file_exists($output)) {
                \mkdir($output, 0777, true);
            }
            $output = realpath($output);

            $count = 0;
            $promises = [];
            $classStorage = null;

            $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
            /** @var \SplFileInfo $file */
            foreach ($ri as $file) {
                $targetPath = $output.DIRECTORY_SEPARATOR.$ri->getSubPathname();
                if ($file->isDir()) {
                    if (!\file_exists($targetPath)) {
                        \mkdir($targetPath, 0777, true);
                    }
                } elseif ($file->isFile()) {
                    if ($file->getExtension() == 'php') {
                        $promise = call(function () use ($plugins, $file, $targetPath, $prefix, $count, &$result, &$promises, &$classStorage) {
                            $res = yield enqueueCallable(
                                [self::class, 'runAsyncInternal'],
                                $plugins,
                                $file->getRealPath(),
                                $targetPath,
                                "$prefix$count.php"
                            );
                            if ($res instanceof ExceptionWrapper) {
                                throw $res->getException();
                            }
                            [$classes, $result] = $res;
                            if ($classStorage) {
                                $classStorage->merge($classes);
                            } else {
                                $classStorage = $classes;
                            }
                            unset($promises[$count]);
                        });
                        $promises[$count] = $promise;
                        if (!($count++ % 10)) {
                            yield $promise;
                        }
                    } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                        \copy($file->getRealPath(), $targetPath);
                    }
                }
            }

            yield $promises;

            if ($classStorage) {
                yield self::runAsync($classStorage->finish(), $output, $output, $prefix);
            }

            return $result;
        });
    }

    /**
     * Run phabel.
     *
     * @param array $plugins   Plugins
     * @param string $input    Input file/directory
     * @param string $output   Output file/directory
     * @param string $coverage Coverage path
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return array<string, string>|ExceptionWrapper
     */
    public static function runAsyncInternal(array $plugins, string $input, string $output, string $coverage = '')
    {
        try {
            return Traverser::runInternal($plugins, $input, $output, $coverage);
        } catch (\Throwable $e) {
            return new ExceptionWrapper($e);
        }
    }

    /**
     * AST traverser.
     *
     * @return SplQueue<SplQueue<PluginInterface>> $queue Plugin queue
     */
    public function __construct(SplQueue $queue = null)
    {
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $this->queue = $queue ?? new SplQueue;
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->printer = new Standard();
    }
    /**
     * Set package name.
     *
     * @param string $package Package name
     *
     * @return void
     */
    public function setPackage(string $package): void
    {
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $this->packageQueue = new SplQueue;
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue;
        foreach ($this->queue as $queue) {
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
     * @param string $file File
     * @param string $output Output file
     *
     * @return int
     */
    public function traverse(string $file, string $output): int
    {
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $reducedQueue = new SplQueue;
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue;
        foreach ($this->packageQueue ?? $this->queue as $queue) {
            if ($newQueue->count()) {
                $reducedQueue->enqueue($newQueue);
                /** @var SplQueue<PluginInterface> */
                $newQueue = new SplQueue;
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
            return 0;
        }

        try {
            $ast = new RootNode($this->parser->parse(\file_get_contents($file)) ?? []);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $message .= " while processing ";
            $message .= $file;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        }

        $this->file = $file;
        $this->outputFile = $output;
        [$it, $result] = $this->traverseAstInternal($ast, $reducedQueue);
        \file_put_contents($output, $result);

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
        $this->file = '';
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
        $result = '';
        do {
            $context = null;
            try {
                foreach ($pluginQueue ?? $this->packageQueue ?? $this->queue as $queue) {
                    $context = new Context;
                    $context->setFile($this->file);
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
                $message .= $this->file;
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
}
