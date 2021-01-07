<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\Traverser;
use PhpParser\Node;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class VariableFinder extends Plugin
{
    /**
     * Singleton
     */
    private static self $singleton;
    /**
     * Traverser
     */
    private static Traverser $singletonTraverser;
    /**
     * Get found closure uses.
     *
     * @param Node $ast Node tree
     * @param bool $byRef Whether to link by ref found variables
     *
     * @return array<string, ClosureUse>
     */
    public static function find(Node $ast, bool $byRef = false): array
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new self;
            self::$singletonTraverser = Traverser::fromPlugin(self::$singleton);
        }
        self::$singleton->setConfig('byRef', $byRef);
        self::$singletonTraverser->traverseAst($ast);
        return self::$singleton->getFound();
    }
    /**
     * Constructor
     */
    private function __construct()
    {
    }
    /**
     * Found array
     */
    private array $found = [];
    /**
     * Enter variable
     *
     * @param Variable $var
     * @return void
     */
    public function enter(Variable $var)
    {
        if (\is_string($var->name) && $var->name !== 'this') {
            $this->found[$var->name]= new ClosureUse($var, $this->getConfig('byRef', false));
        }
    }
    /**
     * Get found closure uses.
     *
     * @return array<string, ClosureUse>
     */
    private function getFound(): array
    {
        $found = $this->found;
        $this->found = [];
        return \array_values($found);
    }
}
