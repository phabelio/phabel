<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Traverser;
use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\Node\Expr\ClassConstFetch;
/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ConstantResolver extends Plugin
{
    /**
     * Singleton.
     */
    private static self $singleton;
    /**
     * Traverser.
     */
    private static Traverser $singletonTraverser;
    /**
     * Class storage plugin.
     */
    private \Phabel\Plugin\ClassStoragePlugin $plugin;
    /**
     * Current class name.
     *
     * @var class-string
     */
    private string $currentClass;
    /**
     * Resolve constants.
     *
     * @return Node
     */
    public static function resolve(Node $ast, string $currentClass, \Phabel\Plugin\ClassStoragePlugin $plugin) : Node
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self();
            self::$singletonTraverser = Traverser::fromPlugin(self::$singleton);
        }
        self::$singleton->plugin = $plugin;
        self::$singleton->currentClass = $currentClass;
        self::$singletonTraverser->traverseAst($ast, null, \false);
        return $ast;
    }
    /**
     * Constructor.
     */
    private function __construct()
    {
    }
    /**
     * Enter class constant lookup.
     */
    public function enter(ClassConstFetch $var)
    {
        // Do not support constant resolution with static for now
        if ((string) $var->class === 'self') {
            $class = $this->currentClass;
        } else {
            $class = self::getFqdn($var->class);
        }
        return \array_values($this->plugin->classes[$class])[0]->constants[$var->name->name];
    }
}
