<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;

/**
 * Expression fixer for PHP 80.
 */
class ConstructorPromotion extends Plugin
{
    public function enter(ClassMethod $classMethod, Context $ctx): void
    {
        if (\strtolower($classMethod->name) !== '__construct' || $classMethod->stmts === null) {
            return;
        }
        foreach ($classMethod->params as $param) {
            if ($param->flags && $param->var instanceof Param) {
                $ctx->insertAfter(
                    $classMethod,
                    new Property(
                        $param->flags,
                        [new PropertyProperty($param->var->name)],
                        [],
                        $param->type
                    )
                );
                \array_unshift(
                    $classMethod->stmts,
                    new Expression(new Assign(
                        new PropertyFetch(new Variable('this'), $param->var->name),
                        new Variable($param->var->name)
                    ))
                );
                $param->flags = 0;
            }
        }
    }
}
