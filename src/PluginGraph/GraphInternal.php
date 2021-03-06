<?php

namespace Phabel\PluginGraph;

use Phabel\Plugin;
use Phabel\PluginInterface;
use SplObjectStorage;
use SplQueue;

/**
 * Internal graph class.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class GraphInternal
{
    /**
     * Plugin nodes, indexed by plugin name+config.
     *
     * @var array<class-string<PluginInterface>, array<string, Node>>
     */
    private array $plugins = [];

    /**
     * Package contexts.
     *
     * @var PackageContext[]
     */
    private array $packageContexts = [];

    /**
     * Stores list of Nodes that are not required by any other node.
     *
     * @var SplObjectStorage<Node, null>
     */
    private SplObjectStorage $unlinkedNodes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->unlinkedNodes = new SplObjectStorage();
    }

    /**
     * Get new package context.
     */
    public function getPackageContext(): PackageContext
    {
        $packageContext = new PackageContext();
        $this->packageContexts[] = $packageContext;

        return $packageContext;
    }

    /**
     * Add plugin.
     *
     * @param string         $plugin Plugin to add
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Package context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin to add
     *
     * @return Node[]
     */
    public function addPlugin(string $plugin, array $config, PackageContext $ctx): array
    {
        return \array_map(fn (array $config): Node => $this->addPluginInternal($plugin, $config, $ctx), $plugin::splitConfig($config));
    }

    /**
     * Add plugin.
     *
     * @param string         $plugin Plugin to add
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Package context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     */
    private function addPluginInternal(string $plugin, array $config, PackageContext $ctx): Node
    {
        $configStr = \json_encode($config);
        if (isset($this->plugins[$plugin][$configStr])) {
            return $this->plugins[$plugin][$configStr]->addPackages($ctx);
        }
        $this->plugins[$plugin][$configStr] = $node = new Node($this, $ctx);
        $this->unlinkedNodes->attach($node);

        return $node->init($plugin, $config);
    }

    /**
     * Set unlinked node as linked.
     */
    public function linkNode(Node $node): void
    {
        if ($this->unlinkedNodes->contains($node)) {
            $this->unlinkedNodes->detach($node);
        }
    }

    /**
     * Flatten graph.
     *
     * @return SplQueue<SplQueue<PluginInterface>>
     */
    public function flatten(): SplQueue
    {
        if (!$this->plugins) {
            /** @psalm-var SplQueue<SplQueue<PluginInterface>> */
            return new SplQueue();
        }
        if ($this->unlinkedNodes->count()) {
            /** @var Node|null $initNode */
            $initNode = null;
            foreach ($this->unlinkedNodes as $node) {
                if ($initNode) {
                    $node = $initNode->merge($node);
                }
                /** @var Node */
                $initNode = $node;
            }
            $this->unlinkedNodes = new SplObjectStorage;
            $this->unlinkedNodes->attach($initNode);
            return $initNode->circular()->flatten();
        }
        return \array_values(\array_values($this->plugins)[0])[0]->circular()->flatten();
    }
    /**
     * Returns graph debug information.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        $res = [];
        foreach ($this->flatten() as $queue) {
            $cur = [];
            foreach ($queue as $plugin) {
                $cur[] = [\get_class($plugin), $plugin->getConfigArray()];
            }
            $res []= $cur;
        }
        return $res;
    }
}
