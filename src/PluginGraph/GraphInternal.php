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
    private $plugins = [];
    /**
     * Package contexts.
     *
     * @var PackageContext[]
     */
    private $packageContexts = [];
    /**
     * Stores list of Nodes that are not required by any other node.
     *
     * @var SplObjectStorage<Node, null>
     */
    private $unlinkedNodes;
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
    public function getPackageContext()
    {
        $packageContext = new PackageContext();
        $this->packageContexts[] = $packageContext;
        $phabelReturn = $packageContext;
        if (!$phabelReturn instanceof PackageContext) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type PackageContext, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    public function addPlugin($plugin, array $config, PackageContext $ctx)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = \array_map(function (array $config) use ($plugin, $ctx) {
            $phabelReturn = $this->addPluginInternal($plugin, $config, $ctx);
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }, $plugin::splitConfig($config));
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
    private function addPluginInternal($plugin, array $config, PackageContext $ctx)
    {
        if (!\is_string($plugin)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $configStr = \var_export($config, true);
        if (isset($this->plugins[$plugin][$configStr])) {
            $phabelReturn = $this->plugins[$plugin][$configStr]->addPackages($ctx);
            if (!$phabelReturn instanceof Node) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $this->plugins[$plugin][$configStr] = $node = new Node($this, $ctx);
        $this->unlinkedNodes->attach($node);
        $phabelReturn = $node->init($plugin, $config);
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Set unlinked node as linked.
     */
    public function linkNode(Node $node)
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
    public function flatten()
    {
        if (!$this->plugins) {
            $phabelReturn = new SplQueue();
            if (!$phabelReturn instanceof SplQueue) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type SplQueue, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            /** @psalm-var SplQueue<SplQueue<PluginInterface>> */
            return $phabelReturn;
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
            $this->unlinkedNodes = new SplObjectStorage();
            $this->unlinkedNodes->attach($initNode);
            $phabelReturn = $initNode->circular()->flatten();
            if (!$phabelReturn instanceof SplQueue) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type SplQueue, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $phabelReturn = \array_values(\array_values($this->plugins)[0])[0]->circular()->flatten();
        if (!$phabelReturn instanceof SplQueue) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type SplQueue, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Returns graph debug information.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $res = [];
        foreach ($this->flatten() as $queue) {
            $cur = [];
            foreach ($queue as $plugin) {
                $cur[] = [\get_class($plugin), $plugin->getConfigArray()];
            }
            $res[] = $cur;
        }
        $phabelReturn = $res;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
