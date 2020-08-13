<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use SplQueue;

/**
 * @author Daniil Gentili <daniil@daniil.it>
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
     * @return SplQueue<SplQueue<Plugin>>
     */
    private SplQueue $packageQueue;
    /**
     * AST traverser.
     *
     * @return SplQueue<SplQueue<Plugin>> $queue Plugin queue
     */
    public function __construct(SplQueue $queue)
    {
        $this->queue = $queue;
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
     * @param string $file    File
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
        $ast = $this->parser->parse(\file_get_contents($file));
        foreach ($reducedQueue as $queue) {
            $this->traverseArray($ast, $queue);
        }
    }
    /**
     * Traverse array of nodes.
     *
     * @param Node[]           $nodes   Nodes
     * @param SplQueue<Plugin> $plugins Plugins
     *
     * @return void
     */
    public function traverseArray(array &$nodes, SplQueue $plugins): void
    {
        foreach ($nodes as &$node) {
            $this->traverseNode($node, $plugins);
        }
    }
    /**
     * Traverse node.
     *
     * @param Node             &$node   Node
     * @param SplQueue<Plugin> $plugins Plugins
     * @return void
     */
    public function traverseNode(Node &$node, SplQueue $plugins): void
    {
        foreach ($plugins as $plugin) {
            foreach (PluginCache::enterMethods(\get_class($plugin)) as $type => $methods) {
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
                    $result = $plugin->{$method}($node);
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
        foreach ($node->getSubNodeNames() as $name) {
            $subNode = &$node->{$name};
            if (\is_array($subNode)) {
                $this->traverseArray($subNode, $plugins);
            } else {
                $this->traverseNode($subNode, $plugins);
            }
        }
        foreach ($plugins as $plugin) {
            foreach (PluginCache::leaveMethods(\get_class($plugin)) as $type => $methods) {
                if (!$node instanceof $type) {
                    continue;
                }
                foreach ($methods as $method) {
                    $result = $plugin->{$method}($node);
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
