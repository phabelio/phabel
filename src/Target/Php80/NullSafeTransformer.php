<?php

namespace Phabel\Target\Php80;

use Phabel\Plugin;
use Phabel\Plugin\NestedExpressionFixer;
use Phabel\Target\Php80\NullSafe\NullSafe;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;

/**
 * Polyfill nullsafe expressions.
 */
class NullSafeTransformer extends Plugin
{
    /**
     * Property fetch.
     *
     * @param NullsafePropertyFetch $fetch
     * @return PropertyFetch
     */
    public function enterPropertyFetch(NullsafePropertyFetch $fetch): PropertyFetch
    {
        return new PropertyFetch(new Coalesce($fetch->var, new StaticPropertyFetch(new FullyQualified(NullSafe::class), 'singleton')), $fetch->name);
    }

    /**
     * Method call.
     *
     * @param NullsafeMethodCall $fetch
     * @return MethodCall
     */
    public function enterMethodCall(NullsafeMethodCall $fetch): MethodCall
    {
        return new MethodCall(new Coalesce($fetch->var, new StaticPropertyFetch(new FullyQualified(NullSafe::class), 'singleton')), $fetch->name, $fetch->args);
    }

    public static function runAfter(array $config): array
    {
        return [NestedExpressionFixer::class => [New_::class => ['class' => [NullsafePropertyFetch::class => true, NullsafeMethodCall::class => true]]]];
    }
}
