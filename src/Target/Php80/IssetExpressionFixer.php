<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;

/**
 * Expression fixer for PHP 80.
 */
class IssetExpressionFixer extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function next(array $config): array
    {
        return [
            fixer::class => [
  'PhpParser\\Node\\Expr\\Eval_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Isset_' =>
  [
    'vars' =>
    [
      'PhpParser\\Node\\Expr\\StaticCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Match_' =>
  [
    'cond' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\MethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\Closure' => true,
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\NullsafeMethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\StaticCall' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\Variable' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Concat' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Function_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\NullsafePropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\Throw_' => true,
      'PhpParser\\Node\\Scalar\\String_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
    ],
    'name' =>
    [
      'string' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Plus' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostDec' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Div' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
      'PhpParser\\Node\\Scalar\\String_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PreInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Plus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Print_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\Print_' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
    ],
    'name' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\StaticPropertyFetch' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Ternary' =>
  [
    'else' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Throw_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryMinus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryPlus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Variable' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Concat' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\YieldFrom' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Yield_' =>
  [
    'key' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
      'PhpParser\\Node\\Expr\\Variable' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\Throw_' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Plus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Function_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Concat' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\New_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Div' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Minus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mod' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Div' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mul' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Assign' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Plus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\UnaryPlus' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\Throw_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Pow' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Concat' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Div' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Equal' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Greater' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Identical' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Minus' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Plus' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
      'PhpParser\\Node\\Scalar\\LNumber' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Pow' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Pow' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\NullsafeMethodCall' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Array_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\NullsafePropertyFetch' => true,
      'PhpParser\\Node\\Expr\\StaticCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Int_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Scalar\\LNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Object_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\String_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Match_' => true,
    ],
  ],
]
        ];
    }
}
