<?php

namespace Phabel\PluginGraph;

use Phabel\PluginCache;
use Phabel\PluginInterface;
use SplObjectStorage;
use SplQueue;

/**
 * Represents a plugin with a certain configuration.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Node
{
    /**
     * Plugins and configs.
     *
     * @var Plugins
     */
    private Plugins $plugin;

    /**
     * Original plugin name.
     *
     * @var class-string<PluginInterface>
     */
    private string $name;

    /**
     * Associated package context.
     *
     * @var PackageContext
     */
    private PackageContext $packageContext;
    /**
     * Nodes that this node requires.
     *
     * @var SplObjectStorage<Node, void>
     */
    private SplObjectStorage $requires;

    /**
     * Nodes that this node extends.
     *
     * @var SplObjectStorage<Node, void>
     */
    private SplObjectStorage $extends;

    /**
     * Nodes that require this node.
     *
     * @var SplObjectStorage<Node, void>
     */
    private SplObjectStorage $requiredBy;

    /**
     * Nodes that extend this node.
     *
     * @var SplObjectStorage<Node, void>
     */
    private SplObjectStorage $extendedBy;

    /**
     * Graph instance.
     */
    private GraphInternal $graph;

    /**
     * Whether this node was visited when looking for circular requirements.
     */
    private bool $visitedCircular = false;

    /**
     * Whether this node can be required, or only extended.
     */
    private bool $canBeRequired = true;

    /**
     * Constructor.
     *
     * @param GraphInternal  $graph  Graph instance
     */
    public function __construct(GraphInternal $graph, PackageContext $ctx)
    {
        $this->packageContext = $ctx;
        $this->graph = $graph;
        $this->requiredBy = new SplObjectStorage;
        $this->extendedBy = new SplObjectStorage;
        $this->requires = new SplObjectStorage;
        $this->extends = new SplObjectStorage;
    }
    /**
     * Initialization function.
     *
     * @param string         $plugin Plugin name
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return self
     */
    public function init(string $plugin, array $config): self
    {
        $this->name = $plugin;
        $this->plugin = new Plugins($plugin, $config);

        $this->canBeRequired = PluginCache::canBeRequired($plugin);
        foreach (PluginCache::runAfter($plugin) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $this->require($node);
            }
        }
        foreach (PluginCache::runBefore($plugin) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $node->require($this);
            }
        }
        foreach (PluginCache::runWithAfter($plugin) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $this->extend($node);
            }
        }
        foreach (PluginCache::runWithBefore($plugin) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $node->extend($this);
            }
        }

        return $this;
    }

    /**
     * Make node require another node.
     *
     * @param self $node Node
     *
     * @return void
     */
    private function require(self $node): void
    {
        if (!$node->canBeRequired) {
            $this->extend($node);
            return;
        }
        if ($this->extends->contains($node) || $node->extendedBy->contains($this)) {
            $this->extends->detach($node);
            $node->extendedBy->detach($this);
        }
        $this->requires->attach($node);
        $node->requiredBy->attach($this);

        $this->graph->linkNode($this);
    }
    /**
     * Make node extend another node.
     *
     * @param self $node Node
     *
     * @return void
     */
    private function extend(self $node): void
    {
        if ($this->requires->contains($node) || $node->requiredBy->contains($this)) {
            return;
        }
        $this->extends->attach($node);
        $node->extendedBy->attach($this);

        $this->graph->linkNode($this);
    }

    /**
     * Merge node with another node.
     *
     * @param self $other Other node
     *
     * @return Node
     */
    public function merge(self $other): Node
    {
        $this->packageContext->merge($other->packageContext);
        $this->plugin->merge($other->plugin);
        foreach ($other->requiredBy as $node) {
            $node->require($this);
            $node->requires->detach($other);
        }
        foreach ($other->extendedBy as $node) {
            $node->extend($this);
            $node->extends->detach($other);
        }

        return $this;
    }

    /**
     * Flatten tree.
     *
     * @return SplQueue<SplQueue<PluginInterface>>
     */
    public function flatten(): SplQueue
    {
        /** @var SplQueue<PluginInterface> */
        $initQueue = new SplQueue;

        /** @var SplQueue<SplQueue<PluginInterface>> */
        $queue = new SplQueue;
        $queue->enqueue($initQueue);

        $this->flattenInternal($queue);

        return $queue;
    }
    /**
     * Look for circular references, while merging package contexts.
     *
     * @return self
     */
    public function circular(): self
    {
        if ($this->visitedCircular) {
            $plugins = [$this->name];
            foreach (\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, DEBUG_BACKTRACE_PROVIDE_OBJECT) as $frame) {
                $plugins []= $frame['object']->name;
                if ($frame['object'] === $this) {
                    break;
                }
            }
            throw new CircularException($plugins);
        }
        $this->visitedCircular = true;

        foreach ($this->requiredBy as $node) {
            $this->packageContext->merge($node->circular()->packageContext);
        }
        foreach ($this->extendedBy as $node) {
            $this->packageContext->merge($node->circular()->packageContext);
        }

        $this->visitedCircular = false;

        return $this;
    }
    /**
     * Internal flattening.
     *
     * @param SplQueue<SplQueue<PluginInterface>> $splQueue Queue
     *
     * @return void
     */
    private function flattenInternal(SplQueue $splQueue): void
    {
        $queue = $splQueue->top();
        $this->plugin->enqueue($queue, $this->packageContext);

        /** @var SplQueue<Node> */
        $extendedBy = new SplQueue;
        $prevNode = null;
        foreach ($this->extendedBy as $node) {
            if (\count($node->requires) + \count($node->extends) === 1) {
                if ($prevNode instanceof self) {
                    $node->merge($prevNode);
                }
                $prevNode = $node;
            } else {
                $extendedBy->enqueue($node);
            }
        }
        if ($prevNode) {
            $extendedBy->enqueue($prevNode);
        }

        /** @var SplQueue<Node> */
        $requiredBy = new SplQueue;
        $prevNode = null;
        foreach ($this->requiredBy as $node) {
            if (\count($node->requires) + \count($node->extends) === 1) {
                if ($prevNode instanceof self) {
                    $node->merge($prevNode);
                }
                $prevNode = $node;
            } else {
                $requiredBy->enqueue($node);
            }
        }
        if ($prevNode) {
            $requiredBy->enqueue($prevNode);
        }



        foreach ($extendedBy as $node) {
            $node->extends->detach($this);
            if (\count($node->extends) + \count($node->requires) === 0) {
                $node->flattenInternal($splQueue);
            }
        }
        foreach ($requiredBy as $node) {
            $node->requires->detach($this);
            if (\count($node->extends) + \count($node->requires) === 0) {
                $splQueue->enqueue(new SplQueue);
                $node->flattenInternal($splQueue);
            }
        }
    }

    /**
     * Add packages from package context.
     *
     * @param PackageContext $ctx Package context
     *
     * @return void
     */
    public function addPackages(PackageContext $ctx): void
    {
        $this->packageContext->merge($ctx);
    }
}
