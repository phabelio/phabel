<?php

namespace Phabel\PluginGraph;

use Phabel\Plugin;
use Phabel\PluginInterface;
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
    private $graph;
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
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($plugin) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($plugin) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
     * @return array{0: SplQueue<SplQueue<PluginInterface>>, 1: array<string, string>}
     */
    public function flatten()
    {
        $plugins = $this->graph->flatten();
        $requires = [];
        foreach ($plugins as $queue) {
            foreach ($queue as $plugin) {
                foreach ($plugin->getComposerRequires() as $package => $constraint) {
                    $requires[$package] = isset($requires[$package]) ? $requires[$package] : [];
                    $requires[$package][] = $constraint;
                }
            }
        }
        $phabelReturn = [$plugins, \array_map(function (array $constraints) {
            $phabelReturn = \implode(':', \array_unique($constraints));
            if (!\is_string($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }, $requires)];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
        $phabelReturn = $this->graph->__debugInfo();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
