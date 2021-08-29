<?php

namespace Phabel\Target\Php80;

use Phabel\Context;
use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;
use Phabel\PhpParser\Node\Expr\Assign;
use Phabel\PhpParser\Node\Expr\PropertyFetch;
use Phabel\PhpParser\Node\Expr\Variable;
use Phabel\PhpParser\Node\Stmt\ClassMethod;
use Phabel\PhpParser\Node\Stmt\Expression;
use Phabel\PhpParser\Node\Stmt\Property;
use Phabel\PhpParser\Node\Stmt\PropertyProperty;
/**
 * Expression fixer for PHP 80.
 */
class ConstructorPromotion extends Plugin
{
    public function enter(ClassMethod $classMethod, Context $ctx)
    {
        if (\strtolower($classMethod->name) !== '__construct') {
            return;
        }
        foreach ($classMethod->params as $param) {
            if ($param->flags) {
                $ctx->insertAfter($classMethod, new Property($param->flags, [new PropertyProperty($param->var->name)], [], $param->type));
                \Phabel\Target\Php73\Polyfill::array_unshift($classMethod->stmts, new Expression(new Assign(new PropertyFetch(new Variable('this'), $param->var->name), new Variable($param->var->name))));
                $param->flags = 0;
            }
        }
    }
}
