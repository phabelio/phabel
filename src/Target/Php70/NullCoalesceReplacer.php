<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use Phabel\Target\Php74\ArrowClosure;
use Phabel\Tools;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Ternary;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class NullCoalesceReplacer extends Plugin
{
    /**
     * Replace null coalesce.
     *
     * @param Coalesce $node Coalesce
     *
     * @return Expr
     */
    public function enter(Coalesce $node): Expr
    {
        if (!Tools::hasSideEffects($node->left)) {
            return new Ternary(new Isset_([$node->left]), $node->left, $node->right);
        }
        $method = 'coalesce';
        if (Tools::hasSideEffects($node->right)) {
            $method = 'coalesceSide';
            $node->right = new ArrowFunction(['expr' => $node->right]);
        }
        if (!($node->left instanceof Node\Expr\ErrorSuppress)) {
            $node->left = new Node\Expr\ErrorSuppress($node->left);
        }
        return self::callPoly($method, $node->left, $node->right);
    }
    /**
     * Coalesce.
     *
     * @param null|mixed $ifNotNull If not null, return this
     * @param mixed      $then      Else this
     *
     * @return mixed
     */
    public static function coalesce($ifNotNull, $then)
    {
        return isset($ifNotNull) ? $ifNotNull : $then;
    }
    /**
     * Coalesce (with side effects on the right).
     *
     * @param null|mixed      $ifNotNull If not null, return this
     * @param callable<mixed> $then      Else this
     *
     * @return mixed
     */
    public static function coalesceSide($ifNotNull, callable $then)
    {
        return isset($ifNotNull) ? $ifNotNull : $then();
    }
    public static function runBefore(): array
    {
        return [ArrowClosure::class];
    }
}
