<?php

namespace Phabel\PluginGraph;

use Phabel\ClassStorage;
use Phabel\Plugin;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\PluginInterface;
use RuntimeException;
use SplQueue;

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
    private GraphInternal $graph;
    /**
     * Constructr.
     */
    public function __construct()
    {
        $this->graph = new GraphInternal;
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
        return $this->graph->addPlugin($plugin, $config, $ctx);
    }
    /**
     * Flatten graph.
     *
     * @return array{0: SplQueue<SplQueue<PluginInterface>>, 1: ?ClassStoragePlugin, 2: array<string, string>}
     */
    public function flatten(): array
    {
        $plugins = $this->graph->flatten();
        $storage = null;
        $requires = [];
        foreach ($plugins as $queue) {
            foreach ($queue as $plugin) {
                foreach ($plugin->getComposerRequires() as $package => $constraint) {
                    $requires[$package] ??= [];
                    $requires[$package][]= $constraint;
                }
                if ($plugin instanceof ClassStoragePlugin) {
                    if ($storage) {
                        throw new RuntimeException('Multiple class storages detected');
                    }
                    $storage = $plugin;
                }
            }
        }
        return [
            $plugins,
            $storage,
            \array_map(fn (array $constraints): string => \implode(':', \array_unique($constraints)), $requires)
        ];
    }

    /**
     * Returns graph debug information.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->graph->__debugInfo();
    }
}
