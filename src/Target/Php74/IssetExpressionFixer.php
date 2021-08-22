<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;

/**
 * Expression fixer for PHP 74.
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
  'PhpParser\\Node\\Expr\\ArrayDimFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Assign' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Pow' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Clone_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Empty_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\UnaryPlus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\ErrorSuppress' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\FuncCall' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Instanceof_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Isset_' =>
  [
    'vars' =>
    [
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\MethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Assign' => true,
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\ShellExec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\New_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Pow' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostDec' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\Closure' => true,
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\StaticCall' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
      'PhpParser\\Node\\Scalar\\LNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PreDec' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Yield_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PreInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Print_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Eval_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryMinus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\PostInc' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Variable' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Yield_' =>
  [
    'value' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\StaticCall' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\New_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Plus' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Concat' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\MethodCall' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Div' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Minus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Yield_' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mod' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mul' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Print_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Plus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Pow' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Yield_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Concat' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\PreInc' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Line' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Div' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Print_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Equal' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Minus' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\StaticCall' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mod' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mul' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Concat' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Plus' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mod' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Pow' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\PreDec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Plus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Bool_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Eval_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Int_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\String_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
]
        ];
    }
}
