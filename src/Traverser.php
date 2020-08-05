<?php

namespace Phabel;

class Traverser
{
    /**
     * Plugins by level.
     *
     * @var array<int, array<class-string<PluginInterface>, array>>
     */
    private array $plugins = [];
    /**
     * Excluded plugins by level.
     *
     * @var array<int, array<class-string<PluginInterface>, bool>>
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
        $this->plugins[$level][$plugin] = $config;
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
        $this->excludedPlugins[$level][$plugin] = $excludeNextLevels;
    }

    /**
     * Add file.
     *
     * @param string $path
     * @param integer $level
     * @return void
     */
    public function addFile(string $path, int $level): void
    {
        if (\in_array($path, $this->files[$level])) {
            return;
        }
        $this->files[$level][] = $path;
    }

    /**
     * Start traversing files.
     *
     * @return void
     */
    public function traverse(): void
    {
    }

    private function resolveCycle(string $class, bool $need, array &$stack): void
    {
        
        $allPlugins = [];
        foreach ($this->plugins as $level => $plugins) {
            foreach ($plugins as $plugin => $config) {
                $needs = self::simpleNeeds($plugin);
                foreach ($needs as $class => $config) {
                    if ()
                }
            }
        }
    }

    /**
     * Simplify need requirements
     *
     * @param class-string<PluginInterface> $class Class to resolve
     * 
     * @return array<class-string<PluginInterface>, array>
     */
    private static function simpleNeeds(string $class): array
    {
        /**
         * @var array<class-string<PluginInterface>, array>[]
         */
        static $cache = [];
        if (isset($cache[$class])) {
            return $cache[$class];
        }
        $needs = $class::needs();
        return $cache[$class] = isset($needs[0]) ? array_fill_keys($needs, []) : $needs
    }

    /**
     * Simplify extend requirements
     *
     * @param class-string<PluginInterface> $class Class to resolve
     * 
     * @return array<class-string<PluginInterface>, array>
     */
    private static function simpleExtends(string $class): array
    {
        /**
         * @var array<class-string<PluginInterface>, array>[]
         */
        static $cache = [];
        if (isset($cache[$class])) {
            return $cache[$class];
        }
        $needs = $class::extends();
        return $cache[$class] = isset($needs[0]) ? array_fill_keys($needs, []) : $needs
    }
}
