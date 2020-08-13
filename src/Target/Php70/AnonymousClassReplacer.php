<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;

class AnonymousClassReplacer extends Plugin
{
    /**
     * @var array
     */
    protected $anonymousClassNodes = [];
    public static $count = 0;
    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$node instanceof Node\Expr\New_) {
            return;
        }

        $classNode = $node->class;
        if (!$classNode instanceof Node\Stmt\Class_) {
            return;
        }

        $newClassName = 'AnonymousClass'.(self::$count++);

        $classNode->name = $newClassName;

        $this->anonymousClassNodes[] = $classNode;

        // Generate new code that instantiate our new class
        $newNode = new Node\Expr\New_(
            new Node\Expr\ConstFetch(
                new Node\Name($newClassName)
            ),
            $node->args
        );

        return $newNode;
    }
}
