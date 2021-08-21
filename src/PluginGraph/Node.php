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
        $other->requiredBy = new SplObjectStorage;
        $other->extendedBy = new SplObjectStorage;
        $this->nameConcat ??= \basename(\str_replace('\\', '/', $this->name));
        $this->nameConcat .= '+';
        $this->nameConcat .= $other->nameConcat ?? \basename(\str_replace('\\', '/', $other->name));
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
                $plugins []= $frame['object']->name;
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
        if ($this->extendedBy->count() || $this->requiredBy->count()) {
            $this->flattenInternal($queue, ' ', true);

            throw new Exception('Graph resolution has stalled');
        }

        return $queue;
    }
    public function n(): string
    {
        $n = \basename(\str_replace('\\', '/', $this->nameConcat ?? $this->name));
        if ($n !== 'NestedExpressionFixer' && $n !== 'IssetExpressionFixer') {
            if ($this->plugin->plugins[$this->name][0]) {
                $n .= \str_replace(['"', '[', ']'], ['\\"', '\[', '\]'], \json_encode($this->plugin->plugins[$this->name][0]));
            }
        } else {
            $n .= \md5(\json_encode($this->plugin->plugins[$this->name][0]));
        }
        $n .= ' '.\spl_object_id($this);
        return '"'.$n.'"';
    }
    public function nn(): string
    {
        return \spl_object_id($this);
    }
    public function explore(array &$result, string $prefix = ' ')
    {
        //echo("$prefix enter extend {$this->name} ".$this->nn().PHP_EOL);
        foreach ($this->extendedBy as $e) {
            $result[$this->n().' -> '.$e->n()." [color=green]"] = true;
            //$e->explore($result, "$prefix ");
        }
        foreach ($this->extends as $e) {
            $result[$e->n().' -> '.$this->n()." [color=green]"] = true;
            //$e->explore($result, "$prefix ");
        }
        //echo("$prefix exit extend {$this->name} ".$this->nn().PHP_EOL);
        //echo("$prefix enter require {$this->name} ".$this->nn().PHP_EOL);
        foreach ($this->requiredBy as $e) {
            $result[$this->n().' -> '.$e->n()." [color=green]"] = true;
            //$e->explore($result, "$prefix ");
        }
        foreach ($this->requires as $e) {
            $result[$e->n().' -> '.$this->n()." [color=green]"] = true;
            //$e->explore($result, "$prefix ");
        }
        //echo("$prefix exit require {$this->name} ".$this->nn().PHP_EOL);
    }
    /**
     * Internal flattening.
     *
     * @param SplQueue<SplQueue<PluginInterface>> $queueOfQueues Queue
     *
     * @return void
     */
    private function flattenInternal(SplQueue $queueOfQueues, $prefix = ' ', bool $print = false)
    {
        do {
            $processedAny = false;
        $queue = $queueOfQueues->top();
        $this->plugin->enqueue($queue, $this->packageContext);
        $this->graph->unprocessedNode->detach($this);

        $prevNode = null;
        $toDetach = new SplQueue;
        foreach ($this->extendedBy as $node) {
            $node->extends->detach($this);
            if (\count($node->requires) + \count($node->extends) === 0) {
                if ($prevNode instanceof self) {
                    $prevNode->merge($node);
                    $toDetach->enqueue($node);
                    $this->graph->unprocessedNode->detach($node);
                } else {
                    $prevNode = $node;
                }
            }
        }
        foreach ($toDetach as $node) {
            $this->extendedBy->detach($node);
        }

        $prevNode = null;
        $toDetach = new SplQueue;
        foreach ($this->requiredBy as $node) {
            $node->requires->detach($this);
            if (\count($node->requires) + \count($node->extends) === 0) {
                if ($prevNode instanceof self) {
                    $prevNode->merge($node);
                    $toDetach->enqueue($node);
                    $this->graph->unprocessedNode->detach($node);
                } else {
                    $prevNode = $node;
                }
            }
        }
        foreach ($toDetach as $node) {
            $this->requiredBy->detach($node);
        }

            do {
                if ($print) {
                    echo("$prefix enter extend {$this->name} ".$this->nn().PHP_EOL);
                }
                $processed = false;
                $toDetach = new SplQueue;
                foreach ($this->extendedBy as $node) {
                    if (\count($node->extends) + \count($node->requires) === 0) {
                        $toDetach->enqueue($node);
                        $node->flattenInternal($queueOfQueues, "$prefix ", $print);
                        $processed = true;
                    } elseif ($print) {
                        //var_dump($node->extends);
                    }
                }
                foreach ($toDetach as $node) {
                    $this->extendedBy->detach($node);
                }
                if ($print) {
                    echo("$prefix exit extend {$this->name} ".$this->nn().PHP_EOL);
                }
                $processedAny = $processedAny || $processed;
            } while ($processed);
            do {
                if ($print) {
                    echo("$prefix enter require {$this->name} ".$this->nn().PHP_EOL);
                }
                $processed = false;
                $toDetach = new SplQueue;
                foreach ($this->requiredBy as $node) {
                    if (\count($node->extends) + \count($node->requires) === 0) {
                        $toDetach->enqueue($node);
                        if (!$queue->isEmpty()) {
                            $queueOfQueues->enqueue(new SplQueue);
                        }
                        $node->flattenInternal($queueOfQueues, "$prefix ", $print);
                        $processed = true;
                    }
                }
                foreach ($toDetach as $node) {
                    $this->requiredBy->detach($node);
                }
                if ($print) {
                    echo("$prefix exit require {$this->name} ".$this->nn().PHP_EOL);
                }
                $processedAny = $processedAny || $processed;
            } while ($processed);
        } while ($processedAny);
        if ($print) {
            echo("$prefix DONE {$this->name} ".$this->nn().PHP_EOL);
        }
    }

    public function __debugInfo()
    {
        $e = \array_map(fn ($f) => $f->name, \iterator_to_array($this->extends));
        $r = \array_map(fn ($f) => $f->name, \iterator_to_array($this->requires));
        $eBy = \array_map(fn ($f) => $f->name, \iterator_to_array($this->extendedBy));
        $rBy = \array_map(fn ($f) => $f->name, \iterator_to_array($this->requiredBy));
        return [
            'name' => "{$this->name} ".$this->nn(),
            'extendedBy' => $eBy,
            'requiredBy' => $rBy,
            'extends' => $e,
            'requires' => $r
        ];
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
