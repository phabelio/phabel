<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\Node\Expr\Isset_;
use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Context
{
    /**
     * Parent nodes stack.
     *
     * @var SplStack<Node>
     */
    public SplStack $parents;
    /**
     * Whether we're inside an isset expression
     */
    public bool $insideIsset = false;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parents = new SplStack;
    }
    /**
     * Push node
     *
     * @param Node $node Node
     * 
     * @return void
     */
    public function push(Node $node): void
    {
        $this->parents->push($node);
        if ($node instanceof Isset_) {
            $this->insideIsset = true;
        }
    }
    /**
     * Pop node
     *
     * @return void
     */
    public function pop(): void
    {
        if ($this->parents->pop() instanceof Isset_) {
            $this->insideIsset = false;
        }
    }
    /**
     * Insert nodes before node.
     *
     * @param Node $node
     * @param Node ...$nodes
     * @return void
     */
    public function insertBefore(Node $node, Node ...$nodes): void
    {
        if (empty($nodes)) {
            return;
        }
        $subNode = $node->getAttribute('currentNode');
        $subNodeIndex = $node->getAttribute('currentNodeIndex');
        \array_splice($node->{$subNode}, $subNodeIndex, 0, $nodes);
        $skips = $node->getAttribute('skipNodes', []);
        $skips []= $subNodeIndex+\count($nodes);
        $node->setAttribute('skipNodes', $skips);
        $node->setAttribute('currentNodeIndex', $subNodeIndex - 1);
    }
    /**
     * Insert nodes after node.
     *
     * @param Node $node
     * @param Node ...$nodes
     * @return void
     */
    public function insertAfter(Node $node, Node ...$nodes): void
    {
        $subNode = $node->getAttribute('currentNode');
        $subNodeIndex = $node->getAttribute('currentNodeIndex');
        \array_splice($node->{$subNode}, $subNodeIndex+1, 0, $nodes);
    }
}
