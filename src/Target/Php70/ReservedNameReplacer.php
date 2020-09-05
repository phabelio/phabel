<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ReservedNameReplacer extends Plugin
{
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node): void
    {
        if (!$node instanceof Node\Expr\MethodCall &&
            !$node instanceof Node\Expr\StaticCall &&
            !$node instanceof Node\Stmt\ClassMethod &&
            !$node instanceof Node\Expr\ClassConstFetch &&
            !$node instanceof Node\Const_
        ) {
            return;
        }
        $name = &$node->name;
        if (!\is_string($name) || !\in_array(\strtolower($name), ['continue', 'empty', 'use', 'default', 'echo'])) {
            return;
        }
        $name .= '_';
    }
}
