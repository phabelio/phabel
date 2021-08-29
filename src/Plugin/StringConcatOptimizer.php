<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr\BinaryOp\Concat;
use Phabel\PhpParser\Node\Scalar\String_;
use SplQueue;
/**
 * Optimizes concatenation of multiple strings.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
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
    public function enter(Concat $concat, Context $ctx)
    {
        if ($ctx->parents->top() instanceof Concat) {
            $phabelReturn = null;
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $concatQueue = new SplQueue();
        $this->enqueue($concat, $concatQueue);
        $newQueue = new SplQueue();
        $prevNode = $concatQueue->dequeue();
        while ($concatQueue->count()) {
            $node = $concatQueue->dequeue();
            if ($node instanceof String_ && $prevNode instanceof String_) {
                $prevNode = new String_($prevNode->value . $node->value);
            } else {
                $newQueue->enqueue($prevNode);
                $prevNode = $node;
            }
        }
        $newQueue->enqueue($prevNode);
        if ($newQueue->count() === 1) {
            $phabelReturn = $newQueue->dequeue();
            if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $concat = new Concat($newQueue->dequeue(), $newQueue->dequeue());
        while ($newQueue->count()) {
            $concat = new Concat($concat, $newQueue->dequeue());
        }
        $phabelReturn = $concat;
        if (!($phabelReturn instanceof Node || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
