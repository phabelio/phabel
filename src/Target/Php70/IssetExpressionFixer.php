<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;
/**
 * Expression fixer for PHP 70.
 */
class IssetExpressionFixer extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function next(array $config) : array
    {
        return [fixer::class => ['Phabel\\PhpParser\\Node\\Expr\\ArrayDimFetch' => ['var' => ['Phabel\\PhpParser\\Node\\Expr\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Assign' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignRef' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BitwiseNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BooleanNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Clone_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Closure' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClosureUse' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ConstFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Empty_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ErrorSuppress' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Eval_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Include_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Instanceof_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Isset_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\New_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Print_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ShellExec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Ternary' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryMinus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryPlus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Yield_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Equal' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Greater' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Identical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Bool_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Double' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Int_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Object_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\DNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\Encapsed' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\LNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Line' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\ClassConstFetch' => ['class' => ['Phabel\\PhpParser\\Node\\Expr\\Assign' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignRef' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BitwiseNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BooleanNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClassConstFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Clone_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Closure' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClosureUse' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Empty_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ErrorSuppress' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Eval_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\FuncCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Include_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Instanceof_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Isset_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\MethodCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\New_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PropertyFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ShellExec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\StaticCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\StaticPropertyFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Ternary' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryMinus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryPlus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Yield_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Equal' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Greater' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Identical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Bool_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Double' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Int_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Object_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\Encapsed' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\PropertyFetch' => ['var' => ['Phabel\\PhpParser\\Node\\Expr\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Assign' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignRef' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BitwiseNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BooleanNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Clone_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Closure' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClosureUse' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Empty_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ErrorSuppress' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Eval_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Include_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Instanceof_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Isset_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\New_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Print_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ShellExec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Ternary' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryMinus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryPlus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Yield_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Equal' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Greater' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Identical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Bool_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Double' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Int_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Object_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\DNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\Encapsed' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\LNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Line' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\StaticPropertyFetch' => ['class' => ['Phabel\\PhpParser\\Node\\Expr\\Assign' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignRef' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BitwiseNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BooleanNot' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClassConstFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Clone_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Closure' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ClosureUse' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Empty_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ErrorSuppress' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Eval_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\FuncCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Include_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Instanceof_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Isset_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\MethodCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\New_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PostInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreDec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PreInc' => \true, 'Phabel\\PhpParser\\Node\\Expr\\PropertyFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\ShellExec' => \true, 'Phabel\\PhpParser\\Node\\Expr\\StaticCall' => \true, 'Phabel\\PhpParser\\Node\\Expr\\StaticPropertyFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Ternary' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryMinus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\UnaryPlus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Yield_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\AssignOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BitwiseXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\BooleanOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Concat' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Div' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Equal' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Greater' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\GreaterOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Identical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalAnd' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalOr' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\LogicalXor' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Minus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mod' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Mul' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\NotIdentical' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Plus' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Pow' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftLeft' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\ShiftRight' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\Smaller' => \true, 'Phabel\\PhpParser\\Node\\Expr\\BinaryOp\\SmallerOrEqual' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Array_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Bool_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Double' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Int_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\Object_' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Cast\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\Encapsed' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]]]];
    }
}
