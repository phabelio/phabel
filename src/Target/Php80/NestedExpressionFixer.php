<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer as fixer;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NestedExpressionFixer extends Plugin
{
    /**
     * Expression fixer for PHP 80.
     *
     * @return array
     */
    public static function runAfter(): array
    {
        return [
            fixer::class => [
  'PhpParser\\Node\\Expr\\ClassConstFetch' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Instanceof_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\Assign' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
      'PhpParser\\Node\\Expr\\Yield_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Concat' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Div' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Plus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Pow' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Concat' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Div' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Greater' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Plus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\MethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\New_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\Yield_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
    ],
  ],
]
        ];
    }
}
