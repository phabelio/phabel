<?php

namespace Phabel\PluginGraph;

use Phabel\PluginCache;
use Phabel\PluginInterface;
use SplQueue;
/**
 * Representation of multiple plugins+configs.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Plugins
{
    /**
     * Plugin configs, indexed by plugin name.
     *
     * @var array<class-string<PluginInterface>, array[]>
     */
    public $plugins = [];
    /**
     * Constructor.
     *
     * @param class-string<PluginInterface> $plugin Plugin
     * @param array                         $config Config
     */
    public function __construct(string $plugin, array $config)
    {
        $this->plugins[$plugin] = [$config];
    }
    /**
     * Merge with other plugins.
     *
     * @param self $other Plugins
     *
     * @return void
     */
    public function merge(self $other)
    {
        foreach ($other->plugins as $plugin => $configs) {
            if (isset($this->plugins[$plugin])) {
                $this->plugins[$plugin] = \array_merge($this->plugins[$plugin], $configs);
            } else {
                $this->plugins[$plugin] = $configs;
            }
        }
    }
    /**
     * Enqueue plugins.
     *
     * @param SplQueue<PluginInterface> $queue
     *
     * @return void
     */
    public function enqueue(SplQueue $queue, \Phabel\PluginGraph\PackageContext $ctx, array &$packages)
    {
        foreach ($this->plugins as $plugin => $configs) {
            foreach ($plugin::mergeConfigs(...$configs) as $config) {
                foreach ($plugin::getComposerRequires($config) as $package => $constraint) {
                    $packages[$package] = $packages[$package] ?? [];
                    $packages[$package][] = $constraint;
                }
                if (PluginCache::isEmpty($plugin)) {
                    continue;
                }
                $pluginObj = new $plugin();
                $pluginObj->setConfigArray($config);
                $pluginObj->setPackageContext($ctx);
                $queue->enqueue($pluginObj);
            }
        }
    }
}
