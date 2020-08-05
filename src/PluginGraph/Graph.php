<?php

namespace Phabel\PluginGraph;

use Phabel\PluginInterface;

class Graph
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
     * @param string         $plugin Plugin to add
     * @param array          $config Plugin configuration
     * @param PackageContext $ctx    Package context
     *
     * @psalm-param class-string<PluginInterface> $plugin Plugin name
     *
     * @return Node[]
     */
    public function addPlugin(string $plugin, array $config, PackageContext $ctx): array
    {
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
        $this->plugins[$plugin][$configStr] = $node = new Node;
        return $node->init($this, $plugin, $config, $ctx);
    }
}
