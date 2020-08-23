<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Scalar\String_;
use SplQueue;

/**
 * Optimizes concatenation of multiple strings.
 */
class StringConcatOptimizer extends Plugin
{
    private function enqueue(Concat $concat, SplQueue $queue)
    {
        if ($concat->left instanceof Concat) {
            $this->enqueue($concat->left, $queue);
        } else {
            $queue->enqueue($concat->left);
        }
        if ($concat->right instanceof Concat) {
            $this->enqueue($concat->right, $queue);
        } else {
            $queue->enqueue($concat->right);
        }
    }
    public function enter(Concat $concat, Context $ctx): ?Node
    {
        if ($ctx->parents->top() instanceof Concat) {
            return;
        }
        $concatQueue = new SplQueue;
        $this->enqueue($concat, $concatQueue);
        $newQueue = new SplQueue;
        $prevNode = $concatQueue->dequeue();
        while ($node = $concatQueue->dequeue()) {
            if ($node instanceof String_ && $prevNode instanceof String_) {
                $prevNode = new String_($prevNode->value.$node->value);
            } else {
                $newQueue->enqueue($prevNode);
                $prevNode = $node;
            }
        }
        $newQueue->enqueue($prevNode);

        if ($newQueue->count() === 1) {
            return $newQueue->dequeue();
        }
        $concat = new Concat($newQueue->dequeue(), $newQueue->dequeue());
        while ($node = $newQueue->dequeue()) {
            $concat = new Concat($concat, $newQueue->dequeue());
        }
        return $concat;
    }
}
