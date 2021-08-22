<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer as fixer;

/**
 * Expression fixer for PHP 74.
 */
class NestedExpressionFixer extends Plugin
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
  'PhpParser\\Node\\Expr\\BitwiseNot' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Clone_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Empty_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\ErrorSuppress' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\FuncCall' =>
  [
    'name' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Include_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Instanceof_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\Clone_' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
      'PhpParser\\Node\\Expr\\Cast\\Int_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Isset_' =>
  [
    'vars' =>
    [
      'PhpParser\\Node\\Expr\\Variable' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Greater' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\MethodCall' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\AssignRef' => true,
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\Closure' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Greater' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
    ],
    'name' =>
    [
      'string' => true,
      'PhpParser\\Node\\Identifier' => true,
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\New_' => true,
      'PhpParser\\Node\\Expr\\StaticPropertyFetch' => true,
      'PhpParser\\Node\\Expr\\UnaryPlus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\New_' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostDec' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\MethodCall' => true,
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PostInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Clone_' => true,
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mod' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PreDec' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
      'PhpParser\\Node\\Expr\\StaticCall' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PreInc' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Print_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\Empty_' => true,
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
      'PhpParser\\Node\\Expr\\MethodCall' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
      'PhpParser\\Node\\Scalar\\String_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Minus' => true,
    ],
    'name' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => true,
      'PhpParser\\Node\\Scalar\\LNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\StaticCall' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Expr\\New_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Ternary' =>
  [
    'if' =>
    [
      'null' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Throw_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryMinus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\UnaryPlus' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\PreDec' => true,
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Expr\\UnaryMinus' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\YieldFrom' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Dir' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Div' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\Clone_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Div' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Pow' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Concat' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\Instanceof_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
      'PhpParser\\Node\\Expr\\Cast\\Bool_' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Function_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Div' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignRef' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Minus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Equal' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
      'PhpParser\\Node\\Expr\\PreInc' => true,
      'PhpParser\\Node\\Expr\\PropertyFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mod' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Concat' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Mul' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ArrayDimFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Plus' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => true,
      'PhpParser\\Node\\Expr\\Cast\\String_' => true,
      'PhpParser\\Node\\Scalar\\String_' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\FuncCall' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
      'PhpParser\\Node\\Scalar\\LNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\Pow' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\Ternary' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\Mul' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Method' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => true,
    ],
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Mod' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\Isset_' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Include_' => true,
      'PhpParser\\Node\\Expr\\PostDec' => true,
      'PhpParser\\Node\\Scalar\\LNumber' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Class_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
      'PhpParser\\Node\\Expr\\Variable' => true,
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Concat' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\Eval_' => true,
      'PhpParser\\Node\\Expr\\Cast\\Double' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\PreDec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Div' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Equal' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BitwiseNot' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Concat' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Greater' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Greater' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Scalar\\DNumber' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Scalar\\String_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Minus' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Include_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mod' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Mul' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\Cast\\Object_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\PostInc' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Minus' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Concat' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\PostDec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Eval_' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Plus' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
      'PhpParser\\Node\\Expr\\Print_' => true,
      'PhpParser\\Node\\Scalar\\DNumber' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Pow' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\ShellExec' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\PostDec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Smaller' =>
  [
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\Div' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\Coalesce' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\BooleanNot' => true,
      'PhpParser\\Node\\Expr\\ClosureUse' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ErrorSuppress' => true,
      'PhpParser\\Node\\Expr\\New_' => true,
      'PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\BinaryOp\\Spaceship' =>
  [
    'left' =>
    [
      'PhpParser\\Node\\Expr\\Cast\\Int_' => true,
    ],
    'right' =>
    [
      'PhpParser\\Node\\Expr\\ClassConstFetch' => true,
      'PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\Array_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Expr\\Array_' => true,
      'PhpParser\\Node\\Expr\\PreDec' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\Cast\\String_' =>
  [
    'expr' =>
    [
      'PhpParser\\Node\\Scalar\\String_' => true,
    ],
  ],
  'PhpParser\\Node\\Scalar\\Encapsed' =>
  [
    'parts' =>
    [
      'PhpParser\\Node\\Expr\\BinaryOp\\Identical' => true,
      'PhpParser\\Node\\Expr\\Cast\\Array_' => true,
      'PhpParser\\Node\\Scalar\\MagicConst\\File' => true,
    ],
  ],
]
        ];
    }
}
