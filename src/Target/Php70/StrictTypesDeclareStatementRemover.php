<?php

namespace Phabel\Target\Php70;

use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

class StrictTypesDeclareStatementRemover extends NodeVisitorAbstract
{
    /**
     * {@inheritdoc}
     */
    public function leave(Declare_ $node): ?int
    {
        $node->declares = \array_filter($node->declares, fn (DeclareDeclare $declare) => $declare->key->name !== 'strict_types');

        if (empty($node->declares)) {
            return NodeTraverser::REMOVE_NODE;
        }
        return null;
    }
}
