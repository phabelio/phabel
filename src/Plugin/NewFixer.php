<?php

namespace Phabel\Plugin;

use Phabel\Context;
use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\NodeFinder;

/**
 * Fix certain new expressions.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 */
class NewFixer extends Plugin
{
    private NodeFinder $finder;
    public function __construct()
    {
        $this->finder = new NodeFinder;
    }
    public function enterNew(New_ $new, Context $context)
    {
        if ($new->class instanceof Expr && !($new->class instanceof FuncCall
            || $new->class instanceof StaticCall
            || $new->class instanceof MethodCall
            || $new->class instanceof NullsafeMethodCall)
            && $this->finder->findFirst($new->class, fn (Node $node): bool => ($node instanceof FuncCall
            || $node instanceof StaticCall
            || $node instanceof MethodCall
            || $node instanceof NullsafeMethodCall))) {
            $valueCopy = $new->class;
            return new Ternary(
                new BooleanOr(
                    new Assign($new->class = $context->getVariable(), $valueCopy),
                    new LNumber(1)
                ),
                $new,
                new LNumber(0)
            );
        }
    }
    public static function runWithBefore(array $config): array
    {
        return [
            NestedExpressionFixer::class => [
                New_::class => [
                    'class' => [
                        NullsafeMethodCall::class => true,
                        StaticCall::class => true,
                        MethodCall::class => true,
                        FuncCall::class => true,
                    ]
                ]
            ]
        ];
    }
}
