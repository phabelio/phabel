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
     */
    private $graph = null;
    /**
     * Resolved graph instance.
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
    public function getPackageContext()
    {
        $phabelReturn = $this->graph->getPackageContext();
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
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return Node[]
     */
    public function addPlugin($plugin, array $config, PackageContext $ctx)
    {
        if (!\is_string($plugin)) {
            if (!(\is_string($plugin) || \is_object($plugin) && \method_exists($plugin, '__toString') || (\is_bool($plugin) || \is_numeric($plugin)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $plugin = (string) $plugin;
        }
        $phabelReturn = $this->graph->addPlugin($plugin, $config, $ctx);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Flatten graph.
     *
     * @return ResolvedGraph
     */
    public function flatten()
    {
        $this->resolvedGraph = isset($this->resolvedGraph) ? $this->resolvedGraph : new ResolvedGraph(...$this->graph->flatten());
        $this->graph = null;
        while (\gc_collect_cycles()) {
        }
        $phabelReturn = $this->resolvedGraph;
        if (!$phabelReturn instanceof ResolvedGraph) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ResolvedGraph, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
