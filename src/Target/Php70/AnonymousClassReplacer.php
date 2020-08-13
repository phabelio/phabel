<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use Spatie\Php7to5\Converter;
use Spatie\Php7to5\Exceptions\InvalidPhpCode;

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
