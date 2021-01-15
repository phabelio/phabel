<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Target\Php71\NullableType;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Target\Php80\UnionTypeStripper;
use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
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
     * Enter new.
     *
     * @param New_    $node New stmt
     * @param Context $ctx  Context
     *
     * @return void
     */
    public function enterNew(New_ $node, Context $ctx): void
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
        $topClass = null;
        foreach ($ctx->parents as $parent) {
            if ($parent instanceof Class_) {
                $topClass = $parent;
            }
        }
        if ($topClass) {
            $ctx->insertAfter($topClass, $classNode);
        } else {
            $ctx->insertBefore($node, $classNode);
        }
    }

    public static function previous(array $config): array
    {
        return [ArrowClosure::class, ReturnTypeHints::class, NullableType::class, UnionTypeStripper::class];
    }
}
