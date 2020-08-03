<?php

namespace Phabel\Target\Php71;

use Phabel\Plugin;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeVisitor\NameResolver;

/**
 * Removes the class constant visibility modifiers (PHP 7.1).
 */
class ClassConstantVisibilityModifiersRemover extends Plugin
{
    /**
     * Makes public private and protected class constants.
     *
     * @param ClassConst $node Constant
     * 
     * @return void
     */
    public function enter(ClassConst $node): void
    {
        $node->flags = 0; // Remove constant modifier
    }

    /**
     * {@inheritDoc}
     */
    public function needs(): string
    {
        return NameResolver::class;
    }
}
