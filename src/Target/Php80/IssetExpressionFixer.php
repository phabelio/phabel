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
    public static function next(array $config) : array
    {
        return [fixer::class => ['Phabel\\PhpParser\\Node\\Expr\\ArrayDimFetch' => ['var' => ['Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true], 'dim' => ['Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\PropertyFetch' => ['var' => ['Phabel\\PhpParser\\Node\\Expr\\ConstFetch' => \true, 'Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true], 'name' => ['Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\StaticPropertyFetch' => ['name' => ['Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'Phabel\\PhpParser\\Node\\Expr\\Variable' => ['name' => ['Phabel\\PhpParser\\Node\\Expr\\Throw_' => \true]]]];
    }
}
