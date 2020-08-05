<?php

namespace Phabel\PluginGraph;

use Phabel\PluginInterface;
use SplObjectStorage;

/**
 * Represents a plugin with a certain configuration.
 */
class Node
{
    /**
     * Plugin name.
     */
    private string $plugin = '';
    /**
     * Plugin configuration.
     */
    private array $config = [];

    /**
     * Associated package contexts.
     */
    private SplObjectStorage $packageContexts;
    /**
     * Nodes that this node requires.
     *
     * @var Node[]
     */
    private array $requires = [];

    /**
     * Nodes that this node extends.
     *
     * @var Node[]
     */
    private array $extends = [];

    /**
     * Nodes that require this node.
     *
     * @var Node[]
     */
    private array $requiredBy = [];

    /**
     * Nodes that extend this node.
     *
     * @var Node[]
     */
    private array $extendedBy = [];

    /**
     * Whether this node was visited when looking for circular requirement references.
     */
    private bool $visitedRequires = false;
    /**
     * Whether this node was visited when looking for circular requirement references.
     */
    private bool $visitedExtends = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->packageContexts = new SplObjectStorage;
    }
    /**
     * Initialization function.
     *
     * @param Graph          $graph  Graph instance
     * @param string         $plugin Plugin name
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return self
     */
    public function init(Graph $graph, string $plugin, array $config, PackageContext $ctx): self
    {
        $this->plugin = $plugin;
        $this->config = $config;
        $this->packageContexts->attach($ctx);

        $requirements = self::simplify($plugin::needs());
        $extends = self::simplify($plugin::extends());

        foreach ($requirements as $class => $config) {
            foreach ($graph->addPlugin($class, $config, $ctx) as $node) {
                $this->requires []= $node;
                $node->requiredBy []= $this;
            }
        }
        foreach ($extends as $class => $config) {
            foreach ($graph->addPlugin($class, $config, $ctx) as $node) {
                $this->extends []= $node;
                $node->extendedBy []= $this;
            }
        }

        return $this;
    }

    /**
     * Simplify requirements.
     *
     * @param (array<class-string<PluginInterface>, array>|class-string<PluginInterface>[]) $requirements Requirements
     *
     * @return array<class-string<PluginInterface>, array>
     */
    private static function simplify(array $requirements): array
    {
        return isset($requirements[0]) ? \array_fill_keys($requirements, []) : $requirements;
    }

    /**
     * Add package context.
     *
     * @param PackageContext $ctx Context
     *
     * @return self
     */
    public function addPackageContext(PackageContext $ctx): self
    {
        if ($this->packageContexts->contains($ctx)) {
            return $this;
        }
        $this->packageContexts->attach($ctx);
        foreach ($this->requires as $node) {
            $node->addPackageContext($ctx);
        }
        foreach ($this->extends as $node) {
            $node->addPackageContext($ctx);
        }
        return $this;
    }

    /**
     * Check if this node requires only one other node.
     *
     * @return boolean
     */
    private function oneRequire(): bool
    {
        return \count($this->requires) === 1 && empty($this->extends);
    }
    /**
     * Check if this node extends only one other node.
     *
     * @return boolean
     */
    private function oneExtend(): bool
    {
        return \count($this->extends) === 1;
    }
}
