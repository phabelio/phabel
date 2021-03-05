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
    private $queue;
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
     * Current file.
     */
    private $file = '';
    /**
     * Current output file.
     */
    private $outputFile = '';
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
        $phabelReturn = new self($final);
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
    private static function startCoverage($coveragePath)
    {
        if (!\is_string($coveragePath)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($coveragePath) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coveragePath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            $phabelReturn = new PhabelAnonymousClassf6a8739fd00697f7809a41cee22e1c016704f4f22d12739b12f967a15a32ec180($coverage, $coveragePath);
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
    public static function run(array $plugins, $input, $output, $coverage = '')
    {
        if (!\is_string($input)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($output)) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($coverage)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($coverage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coverage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        [$classStorage, $packages] = self::runInternal($plugins, $input, $output, $coverage);
        if ($classStorage) {
            self::run($classStorage->finish(), $output, $output, $coverage);
        }
        $phabelReturn = $packages;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    private static function runInternal(array $plugins, $input, $output, $coverage = '')
    {
        if (!\is_string($input)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($output)) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($coverage)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($coverage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coverage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $_ = self::startCoverage($coverage);
        \set_error_handler(function ($errno = 0, $errstr = '', $errfile = '', $errline = -1) {
            if (!\is_int($errno)) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($errno) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errno) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            if (!\is_string($errstr)) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($errstr) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errstr) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            if (!\is_string($errfile)) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($errfile) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errfile) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            if (!\is_int($errline)) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($errline) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($errline) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            // If error is suppressed with @, don't throw an exception
            if (\error_reporting() === 0) {
                $phabelReturn = false;
                if (!\is_bool($phabelReturn)) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                return $phabelReturn;
            }
            throw new Exception($errstr, $errno, null, $errfile, $errline);
            throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        });
        $graph = new Graph();
        foreach ($plugins as $plugin => $config) {
            $graph->addPlugin($plugin, $config, $graph->getPackageContext());
        }
        $graph = $graph->flatten();
        $p = new Traverser($graph->getPlugins());
        if (!\file_exists($input)) {
            throw new \RuntimeException("File {$input} does not exist!");
        }
        if (\is_file($input)) {
            $it = $p->traverse(\realpath($input), $output);
            echo "Transformed " . $input . " in {$it} iterations" . PHP_EOL;
            $phabelReturn = [$graph->getClassStorage(), $graph->getPackages()];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (!\file_exists($output)) {
            \mkdir($output, 0777, true);
        }
        $output = \realpath($output);
        $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
        $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var \SplFileInfo $file */
        foreach ($ri as $file) {
            $targetPath = $output . DIRECTORY_SEPARATOR . $ri->getSubPathname();
            if ($file->isDir()) {
                if (!\file_exists($targetPath)) {
                    \mkdir($targetPath, 0777, true);
                }
            } elseif ($file->isFile()) {
                if ($file->getExtension() == 'php') {
                    $_ = self::startCoverage($coverage);
                    $it = $p->traverse($file->getRealPath(), $targetPath);
                    echo "Transformed " . $file->getRealPath() . " in {$it} iterations" . PHP_EOL;
                } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                    \copy($file->getRealPath(), $targetPath);
                }
            }
        }
        $phabelReturn = [$graph->getClassStorage(), $graph->getPackages()];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    public static function runAsync(array $plugins, $input, $output, $prefix)
    {
        if (!\is_string($input)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($output)) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($prefix)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\interface_exists(Promise::class)) {
            throw new Exception("amphp/parallel must be installed to parallelize transforms!");
        }
        $phabelReturn = call(function () use ($plugins, $input, $output, $prefix) {
            $result = [];
            if (!\file_exists($output)) {
                \mkdir($output, 0777, true);
            }
            $output = \realpath($output);
            $count = 0;
            $promises = [];
            $classStorage = null;
            $it = new \RecursiveDirectoryIterator($input, \RecursiveDirectoryIterator::SKIP_DOTS);
            $ri = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::SELF_FIRST);
            /** @var \SplFileInfo $file */
            foreach ($ri as $file) {
                $targetPath = $output . DIRECTORY_SEPARATOR . $ri->getSubPathname();
                if ($file->isDir()) {
                    if (!\file_exists($targetPath)) {
                        \mkdir($targetPath, 0777, true);
                    }
                } elseif ($file->isFile()) {
                    if ($file->getExtension() == 'php') {
                        $promise = call(function () use ($plugins, $file, $targetPath, $prefix, $count, &$result, &$promises, &$classStorage) {
                            $res = (yield enqueueCallable([self::class, 'runAsyncInternal'], $plugins, $file->getRealPath(), $targetPath, "{$prefix}{$count}.php"));
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
                            (yield $promise);
                        }
                    } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                        \copy($file->getRealPath(), $targetPath);
                    }
                }
            }
            (yield $promises);
            if ($classStorage) {
                (yield self::runAsync($classStorage->finish(), $output, $output, $prefix));
            }
            return $result;
        });
        if (!$phabelReturn instanceof Promise) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Promise, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    public static function runAsyncInternal(array $plugins, $input, $output, $coverage = '')
    {
        if (!\is_string($input)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($input) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($input) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($output)) {
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($coverage)) {
            throw new \TypeError(__METHOD__ . '(): Argument #4 ($coverage) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coverage) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        try {
            return Traverser::runInternal($plugins, $input, $output, $coverage);
        } catch (\Exception $e) {
            return new ExceptionWrapper($e);
        } catch (\Error $e) {
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
        $this->queue = isset($queue) ? $queue : new SplQueue();
        $this->parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $this->printer = new Standard();
    }
    /**
     * Set package name.
     *
     * @param string $package Package name
     *
     * @return void
     */
    public function setPackage($package)
    {
        if (!\is_string($package)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $this->packageQueue = new SplQueue();
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue();
        foreach ($this->queue as $queue) {
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
     * @param string $file File
     * @param string $output Output file
     *
     * @return int
     */
    public function traverse($file, $output)
    {
        if (!\is_string($file)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($output)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($output) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($output) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $reducedQueue = new SplQueue();
        /** @var SplQueue<PluginInterface> */
        $newQueue = new SplQueue();
        foreach (isset($this->packageQueue) ? $this->packageQueue : $this->queue as $queue) {
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
            $phabelReturn = 0;
            if (!\is_int($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        try {
            $ast = new RootNode(null !== ($phabel_695241879162357b = $this->parser->parse(\file_get_contents($file))) ? $phabel_695241879162357b : []);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message .= " while processing ";
            $message .= $file;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        } catch (\Error $e) {
            $message = $e->getMessage();
            $message .= " while processing ";
            $message .= $file;
            throw new Exception($message, (int) $e->getCode(), $e, $e->getFile(), $e->getLine());
        }
        $this->file = $file;
        $this->outputFile = $output;
        [$it, $result] = $this->traverseAstInternal($ast, $reducedQueue);
        \file_put_contents($output, $result);
        $phabelReturn = $it;
        if (!\is_int($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($allowMulti) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowMulti) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $this->file = '';
        $this->outputFile = '';
        $n = new RootNode([&$node]);
        $phabelReturn = null !== ($phabel_dade6e3133c436e8 = $this->traverseAstInternal($n, $pluginQueue, $allowMulti)) && isset($phabel_dade6e3133c436e8[0]) ? $phabel_dade6e3133c436e8[0] : 0;
        if (!\is_int($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            throw new \TypeError(__METHOD__ . '(): Argument #3 ($allowMulti) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($allowMulti) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $it = 0;
        $result = '';
        do {
            $context = null;
            try {
                foreach (isset($pluginQueue) ? $pluginQueue : (isset($this->packageQueue) ? $this->packageQueue : $this->queue) as $queue) {
                    $context = new Context();
                    $context->setFile($this->file);
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
}
if (!\class_exists(PhabelAnonymousClassf6a8739fd00697f7809a41cee22e1c016704f4f22d12739b12f967a15a32ec180::class)) {
    class PhabelAnonymousClassf6a8739fd00697f7809a41cee22e1c016704f4f22d12739b12f967a15a32ec180 implements \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        private $coveragePath;
        private $coverage;
        public function __construct(CodeCoverage $coverage, $coveragePath)
        {
            if (!\is_string($coveragePath)) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($coveragePath) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($coveragePath) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
