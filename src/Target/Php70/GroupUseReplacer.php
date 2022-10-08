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
    public function leave(GroupUse $node): array
    {
        $nodePrefixParts = $node->prefix->parts;
        return \array_map(fn (UseUse $useNode) => $this->createUseNode($nodePrefixParts, $useNode), $node->uses);
    }
    /**
     * Create separate use node.
     *
     * @param string[] $nodePrefixParts Use prefix
     * @param UseUse $useNode Current use node
     *
     * @return Use_ New use node
     */
    protected function createUseNode(array $nodePrefixParts, UseUse $useNode): Use_
    {
        $nodePrefixParts[] = $useNode->name;
        $nameNode = new Node\Name($nodePrefixParts);
        return new Use_([new UseUse($nameNode, $useNode->alias)], $useNode->type);
    }
}
