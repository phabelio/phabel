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
        return [fixer::class => ['Phabel\\PhpParser\\Node\\Expr\\Instanceof_' => ['expr' => ['Phabel\\PhpParser\\Node\\Scalar\\DNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\LNumber' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\String_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Class_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Dir' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\File' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Function_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Line' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Method' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Namespace_' => \true, 'Phabel\\PhpParser\\Node\\Scalar\\MagicConst\\Trait_' => \true]]]];
    }
}
