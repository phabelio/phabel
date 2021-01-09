<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Namespace_;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class AnonymousClassReplacer extends Plugin
{
    /**
     * Anonymous class count.
     */
    private static int $count = 0;
    /**
     * Current file name hash.
     */
    private string $fileName = '';

    /**
     * {@inheritDoc}
     */
    public function shouldRunFile(string $file): bool
    {
        $this->fileName = \hash('sha256', $file);
        return parent::shouldRunFile($file);
    }

    /**
     * Leave new.
     *
     * @param New_    $node New stmt
     * @param Context $ctx  Context
     *
     * @return void
     */
    public function leaveNew(New_ $node, Context $ctx): void
    {
        $classNode = $node->class;
        if (!$classNode instanceof Node\Stmt\Class_) {
            return;
        }
        $classNode->name = new Identifier('PhabelAnonymousClass'.$this->fileName.(self::$count++));

        $node->class = new Node\Name($classNode->name->name);

        foreach ($ctx->parents as $node) {
            if ($node instanceof Namespace_ || $node instanceof RootNode) {
                $ctx->insertBefore($ctx->getCurrentChild($node), $classNode);
                return;
            }
        }
        throw new \RuntimeException('Could not find hook for inserting anonymous class!');
    }
}
