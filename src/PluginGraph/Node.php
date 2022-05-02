<?php

namespace Phabel\PluginGraph;

use Phabel\Exception;
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
    public Plugins $plugin;
    /**
     * Original plugin name.
     *
     * @var class-string<PluginInterface>
     */
    private string $name;
    /**
     * Original plugin name.
     *
     * @var class-string<PluginInterface>
     */
    private string $nameConcat;
    /**
     * Associated package context.
     *
     * @var PackageContext
     */
    private PackageContext $packageContext;
    /**
     * Nodes that this node requires.
     *
     * @var SplObjectStorage<Node, null>
     */
    private SplObjectStorage $requires;
    /**
     * Nodes that this node extends.
     *
     * @var SplObjectStorage<Node, null>
     */
    private SplObjectStorage $extends;
    /**
     * Nodes that require this node.
     *
     * @var SplObjectStorage<Node, null>
     */
    private SplObjectStorage $requiredBy;
    /**
     * Nodes that extend this node.
     *
     * @var SplObjectStorage<Node, null>
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
        $this->requiredBy = new SplObjectStorage();
        $this->extendedBy = new SplObjectStorage();
        $this->requires = new SplObjectStorage();
        $this->extends = new SplObjectStorage();
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
    public function init(string $plugin, array $pluginConfig): self
    {
        $this->name = $plugin;
        $this->plugin = new Plugins($plugin, $pluginConfig);
        $this->canBeRequired = PluginCache::canBeRequired($plugin);
        foreach (PluginCache::next($plugin, $pluginConfig) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $node->require($this);
            }
        }
        foreach (PluginCache::previous($plugin, $pluginConfig) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $this->require($node);
            }
        }
        foreach (PluginCache::withNext($plugin, $pluginConfig) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $node->extend($this);
            }
        }
        foreach (PluginCache::withPrevious($plugin, $pluginConfig) as $class => $config) {
            foreach ($this->graph->addPlugin($class, $config, $this->packageContext) as $node) {
                $this->extend($node);
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
        if ($other->requires->count() || $other->extends->count()) {
            throw new Exception('Cannot merge a node that requires other nodes!');
        }
        $this->packageContext->merge($other->packageContext);
        $this->plugin->merge($other->plugin);
        foreach ($other->requiredBy as $that) {
            $that->require($this);
            $that->requires->detach($other);
        }
        foreach ($other->extendedBy as $that) {
            $that->extend($this);
            $that->extends->detach($other);
        }
        $other->requiredBy = new SplObjectStorage();
        $other->extendedBy = new SplObjectStorage();
        $this->graph->unprocessedNode->detach($other);
        return $this;
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
                $plugins[] = $frame['object']->name;
                if ($frame['object'] === $this) {
                    break;
                }
            }
            throw new CircularException($plugins);
        }
        $this->visitedCircular = true;
        foreach ($this->requiredBy as $that) {
            $this->packageContext->merge($that->circular()->packageContext);
        }
        foreach ($this->extendedBy as $that) {
            $this->packageContext->merge($that->circular()->packageContext);
        }
        $this->visitedCircular = false;
        return $this;
    }
    /**
     * Flatten tree.
     *
     * @return array{0: SplQueue<SplQueue<PluginInterface>>, array<string, list<string>>}
     */
    public function flatten(): array
    {
        /** @var SplQueue<PluginInterface> */
        $initQueue = new SplQueue();
        /** @var SplQueue<SplQueue<PluginInterface>> */
        $queue = new SplQueue();
        $queue->enqueue($initQueue);
        $packages = [];
        $this->flattenInternal($queue, $packages);
        if ($this->extendedBy->count() || $this->requiredBy->count()) {
            throw new Exception('Graph resolution has stalled');
        }
        return [$queue, $packages];
    }
    /**
     * Internal flattening.
     *
     * @param SplQueue<SplQueue<PluginInterface>> $queueOfQueues Queue
     *
     * @return void
     */
    private function flattenInternal(SplQueue $queueOfQueues, array &$packages): void
    {
        $queue = $queueOfQueues->top();
        $this->plugin->enqueue($queue, $this->packageContext, $packages);
        $this->graph->unprocessedNode->detach($this);
        do {
            $processedAny = false;
            $prevNode = null;
            $toDetach = new SplQueue();
            foreach ($this->extendedBy as $node) {
                $node->extends->detach($this);
                if (\count($node->requires) + \count($node->extends) === 0) {
                    if ($prevNode instanceof self) {
                        $prevNode->merge($node);
                        $toDetach->enqueue($node);
                    } else {
                        $prevNode = $node;
                    }
                }
            }
            foreach ($toDetach as $node) {
                $this->extendedBy->detach($node);
                $this->graph->unprocessedNode->detach($node);
            }
            $prevNode = null;
            $toDetach = new SplQueue();
            foreach ($this->requiredBy as $node) {
                $node->requires->detach($this);
                if (\count($node->requires) + \count($node->extends) === 0) {
                    if ($prevNode instanceof self) {
                        $prevNode->merge($node);
                        $toDetach->enqueue($node);
                    } else {
                        $prevNode = $node;
                    }
                }
            }
            foreach ($toDetach as $node) {
                $this->requiredBy->detach($node);
                $this->graph->unprocessedNode->detach($node);
            }
            $toDetach = new SplQueue();
            foreach ($this->extendedBy as $node) {
                if (\count($node->extends) + \count($node->requires) === 0) {
                    $toDetach->enqueue($node);
                    $node->flattenInternal($queueOfQueues, $packages);
                    $processedAny = true;
                }
            }
            foreach ($toDetach as $node) {
                $this->extendedBy->detach($node);
            }
            $toDetach = new SplQueue();
            foreach ($this->requiredBy as $node) {
                if (\count($node->extends) + \count($node->requires) === 0) {
                    $toDetach->enqueue($node);
                    if (!$queue->isEmpty()) {
                        $queueOfQueues->enqueue(new SplQueue());
                    }
                    $node->flattenInternal($queueOfQueues, $packages);
                    $processedAny = true;
                }
            }
            foreach ($toDetach as $node) {
                $this->requiredBy->detach($node);
            }
        } while ($processedAny);
    }
    /**
     * Add packages from package context.
     *
     * @param PackageContext $ctx Package context
     *
     * @return self
     */
    public function addPackages(PackageContext $ctx): self
    {
        $this->packageContext->merge($ctx);
        return $this;
    }
}
