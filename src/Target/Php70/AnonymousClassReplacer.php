<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php74\ArrowClosure;
use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\If_;

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
        $name = 'PhabelAnonymousClass'.$this->fileName.(self::$count++);
        $classNode->name = new Identifier($name);
        $node->class = new Node\Name($name);

        $classNode = new If_(
            new BooleanNot(self::call('class_exists', new ClassConstFetch($node->class, new Identifier('class')))),
            ['stmts' => [$classNode]]
        );
        $ctx->insertBefore($node, $classNode);
        throw new \RuntimeException('Could not find hook for inserting anonymous class!');
    }

    public static function runAfter(array $config): array
    {
        return [ArrowClosure::class];
    }
}
