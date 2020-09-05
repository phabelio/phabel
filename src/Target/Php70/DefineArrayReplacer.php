<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Const_;

/**
 * Converts define() arrays into const arrays.
 */
class DefineArrayReplacer extends Plugin
{
    /**
     * Convert define() arrays into const arrays.
     *
     * @param FuncCall $node Node
     *
     * @return Const_|null
     */
    public function enter(FuncCall $node): ?Const_
    {
        if (!$node->name instanceof Name || $node->name->toString() != 'define') {
            return null;
        }

        $nameNode = $node->args[0]->value;
        $valueNode = $node->args[1]->value;

        if (!$valueNode instanceof Node\Expr\Array_) {
            return null;
        }

        $constNode = new Node\Const_($nameNode->value, $valueNode);

        return new Node\Stmt\Const_([$constNode]);
    }
}
