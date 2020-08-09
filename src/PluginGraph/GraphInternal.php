<?php

namespace Phabel\PluginGraph;

use Phabel\PluginInterface;
use SplObjectStorage;
use SplQueue;

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
     * @var SplObjectStorage<Node, void>
     */
    private SplObjectStorage $unlinkedNodes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->unlinkedNodes = new SplObjectStorage;
    }
    /**
     * Get new package context.
     *
     * @return PackageContext
     */
    public function getPackageContext(): PackageContext
    {
        $packageContext = new PackageContext;
        $this->packageContexts []= $packageContext;
        return $packageContext;
    }

    /**
     * Add plugin.
     *
     * @param class-string<PluginInterface> $plugin Plugin to add
     * @param array                         $config Plugin configuration
     * @param PackageContext                $ctx    Package context
     *
     * @return Node[]
     */
    public function addPlugin(string $plugin, array $config, PackageContext $ctx): array
    {
        if ($config === ['*']) {
            if (isset($this->plugins[$plugin])) {
                return \array_map(fn (Node $node) => $node->addPackageContext($ctx), $this->plugins[$plugin]);
            }
            $config = [];
        }
        $configs = $plugin::splitConfig($config);
        $nodes = [];
        foreach ($configs as $config) {
            $nodes []= $this->addPluginInternal($plugin, $config, $ctx);
        }
        return $nodes;
    }
    /**
     * Add plugin.
     *
     * @param string         $plugin Plugin to add
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Package context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return Node
     */
    private function addPluginInternal(string $plugin, array $config, PackageContext $ctx): Node
    {
        $configStr = \var_export($config, true);
        if (isset($this->plugins[$plugin][$configStr])) {
            return $this->plugins[$plugin][$configStr]->addPackageContext($ctx);
        }
        $this->plugins[$plugin][$configStr] = $node = new Node($this);
        $this->unlinkedNodes->attach($node);
        return $node->init($plugin, $config, $ctx);
    }

    /**
     * Set unlinked node as linked.
     *
     * @param Node $node
     *
     * @return void
     */
    public function linkNode(Node $node): void
    {
        $this->unlinkedNodes->detach($node);
    }


    /**
     * Flatten graph.
     *
     * @return SplQueue<SplQueue<Plugin>>
     */
    public function flatten(): \SplQueue
    {
        if (!$this->plugins) {
            return new \SplQueue;
        }
        if ($this->unlinkedNodes->count()) {
            foreach ($this->unlinkedNodes as $node) {
                if (isset($initNode)) {
                    $node = $initNode->merge($node);
                }
                /** @var Node */
                $initNode = $node;
            }
            return $initNode->circular()->flatten();
        }
        return \array_values(\array_values($this->plugins)[0])[0]->circular()->flatten();
    }
}
