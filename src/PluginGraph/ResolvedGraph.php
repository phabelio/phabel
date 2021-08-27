<?php

namespace Phabel\PluginGraph;

use Phabel\Exception;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\PluginCache;
use Phabel\PluginInterface;
use SplQueue;

/**
 * Resolved graph.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
final class ResolvedGraph
{
    /**
     * Plugins.
     *
     * @psalm-var SplQueue<SplQueue<PluginInterface>>
     */
    private SplQueue $plugins;
    /**
     * Packages.
     *
     * @var array<string, string>
     */
    private array $packages = [];
    /**
     * Class storage.
     */
    private ?ClassStoragePlugin $classStorage = null;

    /**
     * Constructor.
     *
     * @param SplQueue $plugins Resolved plugins
     * @psalm-param SplQueue<SplQueue<PluginInterface>> $plugins Resolved plugins
     */
    public function __construct(SplQueue $plugins)
    {
        $this->plugins = new SplQueue;
        $requires = [];
        foreach ($plugins as $queue) {
            $newQueue = new SplQueue;
            foreach ($queue as $plugin) {
                foreach ($plugin->getComposerRequires() as $package => $constraint) {
                    $requires[$package] ??= [];
                    $requires[$package][]= $constraint;
                }
                if (PluginCache::isEmpty(\get_class($plugin))) {
                    continue;
                }
                if ($plugin instanceof ClassStoragePlugin) {
                    if ($this->classStorage) {
                        $config = $this->classStorage->mergeConfigs(
                            $this->classStorage->getConfigArray(),
                            $plugin->getConfigArray()
                        );
                        if (\count($config) !== 1) {
                            throw new Exception('Could not merge class storage config!');
                        }
                        $this->classStorage->setConfigArray($config[0]);
                        continue;
                    }
                    $this->classStorage = $plugin;
                }
                $newQueue->enqueue($plugin);
            }
            if ($newQueue->count()) {
                $this->plugins->enqueue($newQueue);
            }
        }
        $this->packages = \array_map(
            fn (array $constraints): string => \implode(':', \array_unique($constraints)),
            $requires
        );
    }
    /**
     * Get plugins.
     *
     * @return SplQueue
     * @psalm-return SplQueue<SplQueue<PluginInterface>>
     */
    public function getPlugins(): SplQueue
    {
        return $this->plugins;
    }

    /**
     * Get packages.
     *
     * @return array
     * @psalm-return array<string, string>
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * Get class storage.
     *
     * @return ?ClassStoragePlugin
     */
    public function getClassStorage(): ?ClassStoragePlugin
    {
        return $this->classStorage;
    }

    /**
     * Returns graph debug information.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        $res = [];
        foreach ($this->plugins as $queue) {
            $cur = [];
            foreach ($queue as $plugin) {
                $cur[] = \basename(\str_replace('\\', '/', \get_class($plugin))); //[\get_class($plugin), $plugin->getConfigArray()];
            }
            $res []= $cur;
        }
        return $res;
    }
}
