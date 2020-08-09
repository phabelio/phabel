<?php

namespace Phabel\PluginGraph;

use Phabel\PluginInterface;

class CircularException extends \Exception
{
    /**
     * Plugin array.
     *
     * @var class-string<PluginInterface>[]
     */
    private array $plugins = [];
    /**
     * Constructor.
     *
     * @param class-string<PluginInterface>[] $plugins  Plugin array
     * @param \Throwable                      $previous Previous exception
     */
    public function __construct(array $plugins, \Throwable $previous = null)
    {
        $this->plugins = $plugins;
        parent::__construct("Detected circular reference: ".\implode(" => ", $plugins), 0, $previous);
    }
    /**
     * Get plugins.
     *
     * @return class-string<PluginInterface>[]
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }
}
