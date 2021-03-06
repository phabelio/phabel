<?php

namespace Phabel\PluginGraph;

use Phabel\Plugin\ClassStoragePlugin;
use RuntimeException;
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
        $this->plugins = $plugins;
        $requires = [];
        foreach ($plugins as $queue) {
            foreach ($queue as $plugin) {
                foreach ($plugin->getComposerRequires() as $package => $constraint) {
                    $requires[$package] ??= [];
                    $requires[$package][]= $constraint;
                }
                if ($plugin instanceof ClassStoragePlugin) {
                    if ($this->classStorage) {
                        throw new RuntimeException('Multiple class storages detected');
                    }
                    $this->classStorage = $plugin;
                }
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
}
