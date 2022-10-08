<?php

namespace Phabel\PluginGraph;

use Phabel\Plugin;
use Phabel\PluginInterface;

/**
 * Graph API wrapper.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Graph
{
    /**
     * Graph instance.
     * @var (GraphInternal | null) $graph
     */
    private $graph = null;
    /**
     * Resolved graph instance.
     * @var (ResolvedGraph | null) $resolvedGraph
     */
    private $resolvedGraph = null;
    /**
     * Constructr.
     */
    public function __construct()
    {
        $this->graph = new GraphInternal();
    }
    /**
     * Get new package context.
     *
     * @return PackageContext
     */
    public function getPackageContext(): PackageContext
    {
        return $this->graph->getPackageContext();
    }
    /**
     * Add plugin.
     *
     * @param string $plugin Plugin to add
     * @param array $config Plugin configuration
     * @param PackageContext $ctx Package context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return Node[]
     */
    public function addPlugin(string $plugin, array $config, PackageContext $ctx): array
    {
        return $this->graph->addPlugin($plugin, $config, $ctx);
    }
    /**
     * Flatten graph.
     *
     * @return ResolvedGraph
     */
    public function flatten(): ResolvedGraph
    {
        $this->resolvedGraph = $this->resolvedGraph ?? new ResolvedGraph(...$this->graph->flatten());
        $this->graph = null;
        while (\gc_collect_cycles()) {
        }
        return $this->resolvedGraph;
    }
}
