<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use SplQueue;

/**
 * Internal regenerator traversor.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReGeneratorInternal extends Plugin
{
    /**
     * List of nodes for each case.
     *
     * @var SplQueue<SplQueue<Node>>
     */
    private $states;
    /**
     *
     */
    public function __construct()
    {
        $this->states = new SplQueue();
        $this->states->enqueue(new SplQueue());
    }
    /**
     * Push node to current case.
     *
     * @param Node $node Node
     *
     * @return void
     */
    private function pushNode(Node $node): void
    {
        $this->states->top()->enqueue($node);
    }
    /**
     *
     */
    private function pushState(): int
    {
        $this->states->enqueue(new SplQueue());
        return $this->states->count() - 1;
    }
    /**
     *
     */
    public function enterRoot(FunctionLike $func)
    {
    }
}
