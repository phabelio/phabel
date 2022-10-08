<?php

namespace Phabel\Target\Php70;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\StringConcatOptimizer;
use Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface;
use Phabel\Target\Php71\NullableType;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Target\Php80\UnionTypeStripper;
use PhpParser\Builder\Method;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class AnonymousClassReplacer extends Plugin
{
    /**
     * Anonymous class count.
     * @var int $count
     */
    private static $count = 0;
    /**
     * Enter new.
     *
     * @param New_ $node New stmt
     * @param Context $ctx Context
     *
     * @return void
     */
    public function enterNew(New_ $node, Context $ctx): void
    {
        $classNode = $node->class;
        if (!$classNode instanceof Node\Stmt\Class_) {
            return;
        }
        $className = null;
        if ($classNode->extends) {
            $className = new ClassConstFetch($classNode->extends, new Identifier('class'));
        }
        if (!$className) {
            foreach ($classNode->implements as $name) {
                $className = new ClassConstFetch($name, new Identifier('class'));
                break;
            }
        }
        if ($className) {
            $className = new Concat($className, new String_('@anonymous'));
        } else {
            $className = new String_('class@anonymous');
        }
        $name = 'PhabelAnonymousClass' . \hash('sha256', $ctx->getOutputFile()) . self::$count++;
        $classNode->stmts[] = (new Method('getPhabelOriginalName'))->makePublic()->makeStatic()->addStmt(new Return_($className))->getNode();
        $classNode->implements[] = new FullyQualified(AnonymousClassInterface::class);
        $classNode->name = new Identifier($name);
        $node->class = new Node\Name($name);
        $ctx->nameResolver->enterNode($classNode);
        $classNode = new If_(new BooleanNot(self::call('class_exists', new ClassConstFetch($node->class, new Identifier('class')))), ['stmts' => [$classNode]]);
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
    /**
     *
     */
    public static function previous(array $config): array
    {
        return [ArrowClosure::class, ReturnTypeHints::class, NullableType::class, UnionTypeStripper::class];
    }
    /**
     *
     */
    public static function next(array $config): array
    {
        return [StringConcatOptimizer::class];
    }
}
