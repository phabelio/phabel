<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer as fixer;

class NestedExpressionFixer extends Plugin
{
    /**
     * Expression fixer for PHP 80.
     *
     * @return array
     */
    public static function runAfter(array $config): array
    {
        return [
            fixer::class => [
  'PhpParser\\Node\\Expr\\ArrayDimFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
    'dim' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Assign' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BooleanNot' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Clone_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Empty_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\ErrorSuppress' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Eval_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\FuncCall' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Instanceof_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\Assign' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\Clone_' => true,
      'PhpParser\\Node\\Expr\\Closure' => true,
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\Eval_' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\New_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\PreInc' => true,
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
      'PhpParser\\Node\\Expr\\UnaryPlus' => true,
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
      'PhpParser\\Node\\Expr\\BinaryOp\\Pow' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
      'PhpParser\\Node\\Expr\\Cast\\Int_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Function_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\MethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\New_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\Assign' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\Clone_' => true,
      'PhpParser\\Node\\Expr\\Closure' => true,
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\Eval_' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\New_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\PreInc' => true,
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
      'PhpParser\\Node\\Expr\\UnaryPlus' => true,
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
      'PhpParser\\Node\\Expr\\BinaryOp\\Pow' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
      'PhpParser\\Node\\Expr\\Cast\\Int_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Function_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Print_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\StaticPropertyFetch' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Ternary' =>
  [
    'if' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
    'else' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Throw_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryMinus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryPlus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Variable' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\YieldFrom' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Yield_' =>
  [
    'value' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
    'key' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Coalesce' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Concat' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Div' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Minus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mod' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mul' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Plus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Pow' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Concat' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Div' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Equal' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Greater' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Identical' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Minus' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mod' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mul' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Plus' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Pow' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Array_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Bool_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Double' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Int_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Object_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\String_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Throw_' => true,
    ],
  ],
]
        ];
    }
}
