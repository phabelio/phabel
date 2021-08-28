<?php

namespace Phabel\PluginGraph;

use Phabel\Exception;
use Phabel\Plugin\ClassStoragePlugin;
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
    private $plugins;
    /**
     * Packages.
     *
     * @var array<string, string>
     */
    private $packages = [];
    /**
     * Class storage.
     */
    private $classStorage = null;
    /**
     * Constructor.
     *
     * @param SplQueue<SplQueue<PluginInterface>> $plugins Resolved plugins
     * @param array<string, list<string>> $packages
     */
    public function __construct(SplQueue $plugins, array $packages = [])
    {
        $this->packages = \array_map(function (array $constraints) {
            $phabelReturn = \implode(':', \array_unique($constraints));
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (string) $phabelReturn;
            }
            return $phabelReturn;
        }, $packages);
        $this->plugins = new SplQueue();
        foreach ($plugins as $queue) {
            $newQueue = new SplQueue();
            foreach ($queue as $plugin) {
                if ($plugin instanceof ClassStoragePlugin) {
                    if ($this->classStorage) {
                        $config = $this->classStorage->mergeConfigs($this->classStorage->getConfigArray(), $plugin->getConfigArray());
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
    }
    /**
     * Get plugins.
     *
     * @return SplQueue
     * @psalm-return SplQueue<SplQueue<PluginInterface>>
     */
    public function getPlugins()
    {
        $phabelReturn = $this->plugins;
        if (!$phabelReturn instanceof SplQueue) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type SplQueue, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get packages.
     *
     * @return array
     * @psalm-return array<string, string>
     */
    public function getPackages()
    {
        $phabelReturn = $this->packages;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get class storage.
     *
     * @return ?ClassStoragePlugin
     */
    public function getClassStorage()
    {
        $phabelReturn = $this->classStorage;
        if (!($phabelReturn instanceof ClassStoragePlugin || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?ClassStoragePlugin, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        foreach ($this->plugins as $queue) {
            $cur = [];
            foreach ($queue as $plugin) {
                $cur[] = \basename(\str_replace('\\', '/', \get_class($plugin)));
                //[\get_class($plugin), $plugin->getConfigArray()];
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
