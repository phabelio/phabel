<?php

namespace Phabel\PhpParser;

use Phabel\PhpParser\NodeVisitor\FindingVisitor;
use Phabel\PhpParser\NodeVisitor\FirstFindingVisitor;
class NodeFinder
{
    /**
     * Find all nodes satisfying a filter callback.
     *
     * @param Node|Node[] $nodes  Single node or array of nodes to search in
     * @param callable    $filter Filter callback: function(Node $node) : bool
     *
     * @return Node[] Found nodes satisfying the filter callback
     */
    public function find($nodes, callable $filter)
    {
        if (!\is_array($nodes)) {
            $nodes = [$nodes];
        }
        $visitor = new FindingVisitor($filter);
        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($nodes);
        $phabelReturn = $visitor->getFoundNodes();
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Find all nodes that are instances of a certain class.
     *
     * @param Node|Node[] $nodes Single node or array of nodes to search in
     * @param string      $class Class name
     *
     * @return Node[] Found nodes (all instances of $class)
     */
    public function findInstanceOf($nodes, $class)
    {
        if (!\is_string($class)) {
            if (!(\is_string($class) || \is_object($class) && \method_exists($class, '__toString') || (\is_bool($class) || \is_numeric($class)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $class = (string) $class;
            }
        }
        $phabelReturn = $this->find($nodes, function ($node) use($class) {
            return \Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($node, $class);
        });
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Find first node satisfying a filter callback.
     *
     * @param Node|Node[] $nodes  Single node or array of nodes to search in
     * @param callable    $filter Filter callback: function(Node $node) : bool
     *
     * @return null|Node Found node (or null if none found)
     */
    public function findFirst($nodes, callable $filter)
    {
        if (!\is_array($nodes)) {
            $nodes = [$nodes];
        }
        $visitor = new FirstFindingVisitor($filter);
        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($nodes);
        return $visitor->getFoundNode();
    }
    /**
     * Find first node that is an instance of a certain class.
     *
     * @param Node|Node[] $nodes  Single node or array of nodes to search in
     * @param string      $class Class name
     *
     * @return null|Node Found node, which is an instance of $class (or null if none found)
     */
    public function findFirstInstanceOf($nodes, $class)
    {
        if (!\is_string($class)) {
            if (!(\is_string($class) || \is_object($class) && \method_exists($class, '__toString') || (\is_bool($class) || \is_numeric($class)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $class = (string) $class;
            }
        }
        return $this->findFirst($nodes, function ($node) use($class) {
            return \Phabel\Target\Php70\ThrowableReplacer::isInstanceofThrowable($node, $class);
        });
    }
}
