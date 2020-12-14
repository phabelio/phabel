<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
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
     * @return SplQueue<SplQueue<Plugin>>
     */
    private SplQueue $queue;
    /**
     * Parser instance.
     */
    private Parser $parser;
    /**
     * Plugin queue for specific package.
     *
     * @return SplQueue<SplQueue<Plugin>>|null
     */
    private ?SplQueue $packageQueue;
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
     * AST traverser.
     *
     * @return SplQueue<SplQueue<Plugin>> $queue Plugin queue
     */
    public function __construct(SplQueue $queue = null)
    {
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
        $this->packageQueue = new SplQueue;
        $newQueue = new SplQueue;
        foreach ($this->queue as $queue) {
            if ($newQueue->count()) {
                $this->packageQueue->enqueue($newQueue);
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
     *
     * @return void
     */
    public function traverse(string $file): void
    {
        /** @var SplQueue<SplQueue<Plugin>> */
        $reducedQueue = new SplQueue;
        $newQueue = new SplQueue;
        foreach ($this->packageQueue ?? $this->queue as $queue) {
            if ($newQueue->count()) {
                $reducedQueue->enqueue($newQueue);
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
        $ast = new RootNode($this->parser->parse(\file_get_contents($file)) ?? []);
        $this->traverseAst($ast, $reducedQueue);
        $printer = new Standard();
        file_put_contents($file, $printer->prettyPrintFile($ast->stmts));
    }
    /**
     * Traverse AST.
     *
     * @param Node     $node        Initial node
     * @param SplQueue $pluginQueue Plugin queue (optional)
     *
     * @return Context
     */
    public function traverseAst(Node &$node, SplQueue $pluginQueue = null): Context
    {
        $context = new Context;
        $context->push($node);
        foreach ($pluginQueue ?? $this->packageQueue ?? $this->queue as $queue) {
            $this->traverseNode($node, $queue, $context);
        }
        return $context;
    }
    /**
     * Traverse node.
     *
     * @param Node             &$node   Node
     * @param SplQueue<Plugin> $plugins Plugins
     * @param Context          $context Context
     *
     * @return void
     */
    public function traverseNode(Node &$node, SplQueue $plugins, Context $context): void
    {
        foreach ($plugins as $plugin) {
            foreach (PluginCache::enterMethods(\get_class($plugin)) as $type => $methods) {
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
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
        foreach ($node->getSubNodeNames() as $name) {
            $node->setAttribute('currentNode', $name);

            $subNode = &$node->{$name};
            if (\is_array($subNode)) {
                for ($index = 0; $index < \count($subNode);) {
                    $node->setAttribute('currentNodeIndex', $index);
                    if ($subNode[$index] instanceof Node) {
                        $this->traverseNode($subNode[$index], $plugins, $context);
                    }
                    $index = $node->getAttribute('currentNodeIndex');
                    do {
                        $index++;
                    } while (\in_array($index, $node->getAttribute('skipNodes', [])));
                }
                $node->setAttribute('skipNodes', []);
            } elseif ($subNode instanceof Node) {
                $this->traverseNode($subNode, $plugins, $context);
            }
        }
        $context->pop();
        foreach ($plugins as $plugin) {
            foreach (PluginCache::leaveMethods(\get_class($plugin)) as $type => $methods) {
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
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
