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
    public static function next(array $config)
    {
        $phabelReturn = [fixer::class => ['PhpParser\\Node\\Expr\\ArrayDimFetch' => ['var' => ['PhpParser\\Node\\Expr\\Throw_' => true], 'dim' => ['PhpParser\\Node\\Expr\\Throw_' => true]], 'PhpParser\\Node\\Expr\\PropertyFetch' => ['var' => ['PhpParser\\Node\\Expr\\ConstFetch' => true, 'PhpParser\\Node\\Expr\\Throw_' => true], 'name' => ['PhpParser\\Node\\Expr\\Throw_' => true]], 'PhpParser\\Node\\Expr\\StaticPropertyFetch' => ['name' => ['PhpParser\\Node\\Expr\\Throw_' => true]], 'PhpParser\\Node\\Expr\\Variable' => ['name' => ['PhpParser\\Node\\Expr\\Throw_' => true]]]];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
