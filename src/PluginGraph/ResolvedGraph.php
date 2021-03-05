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
                    $requires[$package] = isset($requires[$package]) ? $requires[$package] : [];
                    $requires[$package][] = $constraint;
                }
                if ($plugin instanceof ClassStoragePlugin) {
                    if ($this->classStorage) {
                        throw new RuntimeException('Multiple class storages detected');
                    }
                    $this->classStorage = $plugin;
                }
            }
        }
        $this->packages = \array_map(function (array $constraints) {
            $phabelReturn = \implode(':', \array_unique($constraints));
            if (!\is_string($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }, $requires);
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
}
