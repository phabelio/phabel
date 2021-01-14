<?php

namespace Phabel;

use Phabel\PluginGraph\Graph;
use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use SplQueue;

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
     * Run phabel.
     *
     * @param array $plugins Plugins
     * @param string $input  Input file/directory
     * @param string $output Output file/directory
     *
     * @psalm-param array<class-string<PluginInterface>, array> $plugins
     *
     * @return array<string, string>
     */
    public static function run(array $plugins, string $input, string $output): array
    {
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

        [$plugins, $packages] = $graph->flatten();
        $p = new Traverser($plugins);

        if (\is_file($input)) {
            $p->traverse($input, $output);
            return $packages;
        }

        if (!\file_exists($output)) {
            \mkdir($output, 0777, true);
        }

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
                    echo("Transforming ".$file->getRealPath().PHP_EOL);
                    $p->traverse($file->getRealPath(), $targetPath);
                } elseif (\realpath($targetPath) !== $file->getRealPath()) {
                    \copy($file->getRealPath(), $targetPath);
                }
            }
        }

        return $packages;
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
     * @return void
     */
    public function traverse(string $file, string $output): void
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
            return;
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
        $printer = new Standard();

        $result = '';
        do {
            $this->traverseAstInternal($ast, $reducedQueue);
            $oldResult = $result;
            $result = $printer->prettyPrintFile($ast->stmts);
            /*if ($oldResult) {
                \file_put_contents('/tmp/old', $oldResult);
                \file_put_contents('/tmp/new', $result);
                \passthru("diff --color /tmp/old /tmp/new");
            }
            echo "Running...\n";*/
            while (\gc_collect_cycles());
        } while ($result !== $oldResult);
        \file_put_contents($output, $result);
    }
    /**
     * Traverse AST.
     *
     * @param Node     $node        Initial node
     * @param SplQueue $pluginQueue Plugin queue (optional)
     *
     * @psalm-param SplQueue<SplQueue<PluginInterface>> $pluginQueue
     *
     * @return void
     */
    public function traverseAst(Node &$node, SplQueue $pluginQueue = null): void
    {
        $this->file = '';
        $n = new RootNode([&$node]);
        $this->traverseAstInternal($n, $pluginQueue);
    }
    /**
     * Traverse AST.
     *
     * @param RootNode &$node        Initial node
     * @param SplQueue $pluginQueue Plugin queue (optional)
     *
     * @psalm-param SplQueue<SplQueue<PluginInterface>> $pluginQueue
     *
     * @psalm-suppress ReferenceConstraintViolation
     *
     * @return void
     */
    private function traverseAstInternal(RootNode &$node, SplQueue $pluginQueue = null): void
    {
        $context = null;
        try {
            foreach ($pluginQueue ?? $this->packageQueue ?? $this->queue as $queue) {
                $context = new Context();
                $context->push($node);
                $this->traverseNode($node, $queue, $context);
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
