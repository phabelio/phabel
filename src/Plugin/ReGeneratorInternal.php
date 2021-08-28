<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\FunctionLike;
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
    private function pushNode(Node $node)
    {
        $this->states->top()->enqueue($node);
    }
    private function pushState()
    {
        $this->states->enqueue(new SplQueue());
        $phabelReturn = $this->states->count() - 1;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
    public function enterRoot(FunctionLike $func)
    {
    }
}
