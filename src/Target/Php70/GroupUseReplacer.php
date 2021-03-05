<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class GroupUseReplacer extends Plugin
{
    /**
     * Replace group use with multiple use statements.
     *
     * @param GroupUse $node Group use statement
     *
     * @return Use_[]
     */
    public function leave(GroupUse $node)
    {
        $nodePrefixParts = $node->prefix->parts;
        $phabelReturn = \array_map(function (UseUse $useNode) use ($nodePrefixParts) {
            return $this->createUseNode($nodePrefixParts, $useNode);
        }, $node->uses);
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Create separate use node.
     *
     * @param string[] $nodePrefixParts Use prefix
     * @param UseUse   $useNode         Current use node
     *
     * @return Use_ New use node
     */
    protected function createUseNode(array $nodePrefixParts, UseUse $useNode)
    {
        $nodePrefixParts[] = $useNode->name;
        $nameNode = new Node\Name($nodePrefixParts);
        $phabelReturn = new Use_([new UseUse($nameNode, $useNode->alias)], $useNode->type);
        if (!$phabelReturn instanceof Use_) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Use_, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
