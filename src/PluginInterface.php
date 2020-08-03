<?php

namespace Phabel;

use PhpParser\NodeVisitor;

interface PluginInterface
{
    /**
     * Specify which plugins does this plugin require.
     *
     * At each level, the traverser will execute the enter and leave methods of the specified plugins, completing a full AST traversal before starting a new AST traversal for the current plugin.
     * Of course, this increases complexity as it forces an additional traversal of the AST.
     *
     * When possible, use the extends method to reduce complexity.
     *
     * @return array|string Plugin name(s)
     *
     * @psalm-return array<class-string<Plugin|NodeVisitor>>|class-string<Plugin|NodeVisitor>
     */
    public function needs();
    /**
     * Specify which plugins does this plugin extends.
     *
     * At each depth level, the traverser will first execute the enter|leave methods of the specified plugins, then immediately execute the enter|leave methods of the current plugin.
     *
     * This is preferred, and allows only a single traversal of the AST.
     *
     * @return array|string Plugin name(s)
     *
     * @psalm-return array<class-string<Plugin|NodeVisitor>>|class-string<Plugin|NodeVisitor>
     */
    public function extends();

    /**
     * Get configuration key
     *
     * @param string $key Key
     * 
     * @return mixed
     */
    public function getConfig(string $key);

    /**
     * Set configuration key
     *
     * @param string $key   Key
     * @param mixed  $value Value
     * 
     * @return void
     */
    public function setConfig(string $key, $value): void;
}
