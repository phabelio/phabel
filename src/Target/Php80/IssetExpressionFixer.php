<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\IssetExpressionFixer as fixer;

class IssetExpressionFixer extends Plugin
{
    /**
     * Expression fixer for PHP 80.
     *
     * @return array
     */
    public static function runAfter(): array
    {
        return [
            fixer::class => [
  'PhpParser\\Node\\Expr\\ClassConstFetch' =>
  [
    'class' =>
    [
      'PhpParser\\Node\\Scalar\\Encapsed' => true,
    ],
  ],
  'PhpParser\\Node\\Expr\\PropertyFetch' =>
  [
    'var' =>
    [
      'PhpParser\\Node\\Expr\\ConstFetch' => true,
    ],
  ],
]
        ];
    }
}
