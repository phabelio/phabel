<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Traverser;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;

/**
 * Turn an arrow function into a closure.
 */
class ArrowClosure
{
    /**
     * Traverser.
     */
    private Traverser $traverser;
    /**
     * Finder plugin.
     */
    private Plugin $finderPlugin;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->finderPlugin = new class extends Plugin {
            private array $found = [];
            public function enter(Variable $var)
            {
                $this->found[$var->name]= new ClosureUse($var, true);
            }
            public function getFound(): array
            {
                $found = $this->found;
                $this->found = [];
                return $found;
            }
        };
        $this->traverser = Traverser::fromPlugin($this->finderPlugin);
    }
    /**
     * Enter arrow function.
     *
     * @param ArrowFunction $func Arrow function
     *
     * @return Closure
     */
    public function enterClosure(ArrowFunction $func): Closure
    {
        $nodes = [];
        foreach ($func->getSubNodeNames() as $node) {
            $nodes[$node] = $func->{$node};
        }
        $params = [];
        foreach ($nodes['params'] ?? [] as $param) {
            $params[$param->var->name] = true;
        }
        $this->traverser->traverseAst($func);
        $nodes['uses'] = \array_merge(
            \array_values(\array_diff_key(
                $this->finderPlugin->getFound(),
                $params
            )),
            $nodes['use'] ?? []
        );
        return new Closure($nodes, $func->getAttributes());
    }
}
