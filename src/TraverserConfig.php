<?php

namespace Phabel;

class TraverserConfig
{
    /**
     * Plugin configurations, indexed by level.
     *
     * @var array<int, array<class-string<PluginInterface>, array<0: bool, 1: array>[]>>
     */
    private array $plugins = [];
    /**
     * Excluded plugins by level.
     *
     * @var array<int, array<class-string<PluginInterface>, array[]>>
     */
    private array $excludedPlugins = [];
    /**
     * Files indexed by level.
     *
     * @var array<int, string[]>
     */
    private array $files = [];
    /**
     * Add plugin at a certain dependency level.
     *
     * @param class-string<PluginInterface> $plugin Plugin to add
     * @param array                         $config Plugin configuration
     * @param integer                       $level  Dependency level
     *
     * @return void
     */
    public function addPlugin(string $plugin, array $config, int $level): void
    {
        $this->plugins[$level][$plugin] []= $config;
    }
    /**
     * Get all plugins at a certain level.
     *
     * @param integer $level Level
     *
     * @return array
     */
    public function getPlugins(int $level): array
    {
        return $this->plugins[$level];
    }
    /**
     * Exclude plugin at a certain dependency level.
     *
     * @param class-string<PluginInterface> $plugin            Plugin to exclude
     * @param bool                          $excludeNextLevels Whether to exclude plugin from next levels, too
     * @param integer                       $level             Dependency level
     *
     * @return void
     */
    public function excludePlugin(string $plugin, bool $excludeNextLevels, int $level): void
    {
        $this->excludedPlugins[$level][$plugin][] = $excludeNextLevels;
    }

    /**
     * Get final plugin array.
     *
     * @return array<int, array<class-string<PluginInterface, array>>>
     */
    public function final(): array
    {
        \ksort($this->plugins);
        \ksort($this->excludedPlugins);
        return [];
    }

    /**
     * Trickle plugins down the dependency tree.
     *
     * @return void Insert 1% joke
     */
    private function trickleDown(): void
    {
        $met = [];
        $maxLevel = \array_key_last($this->plugins);
        foreach ($this->plugins as $level => $plugins) {
            foreach ($plugins as $plugin => $config) {
                $found = false;
                for ($checkLevel = $level + 1; $checkLevel <= $maxLevel; $checkLevel++) {
                    if (isset($this->plugins[$checkLevel][$plugin])
                        && $this->plugins[$checkLevel][$plugin] !== $config) {
                        $found = true;
                        break;
                    }
                    $this->plugins[$checkLevel][$plugin] = $config;
                }
                $checkLevel--;
            }
        }
    }
}
