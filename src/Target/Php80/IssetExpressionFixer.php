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
        return [fixer::class => ['PhabelVendor\\PhpParser\\Node\\Expr\\ArrayDimFetch' => ['var' => ['PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true], 'dim' => ['PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'PhabelVendor\\PhpParser\\Node\\Expr\\PropertyFetch' => ['var' => ['PhabelVendor\\PhpParser\\Node\\Expr\\ConstFetch' => \true, 'PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true], 'name' => ['PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'PhabelVendor\\PhpParser\\Node\\Expr\\StaticPropertyFetch' => ['name' => ['PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true]], 'PhabelVendor\\PhpParser\\Node\\Expr\\Variable' => ['name' => ['PhabelVendor\\PhpParser\\Node\\Expr\\Throw_' => \true]]]];
    }
}
