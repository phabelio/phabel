<?php

namespace Phabel\Target\Php73;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer as fixer;
/**
 * Expression fixer for PHP 73.
 */
class NestedExpressionFixer extends Plugin
{
    /**
     * {@inheritDoc}
     */
    public static function next(array $config) : array
    {
        return [fixer::class => ['PhabelVendor\\PhpParser\\Node\\Expr\\Instanceof_' => ['expr' => ['PhabelVendor\\PhpParser\\Node\\Scalar\\DNumber' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\LNumber' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\String_' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Line' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'PhabelVendor\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]]]];
    }
}
