<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\StaticCall;

class NullCoalesceReplacer extends Plugin
{
    /**
     * Replace null coalesce
     *
     * @param Coalesce $node Coalesce
     * 
     * @return StaticCall
     */
    public function enter(Coalesce $node): StaticCall
    {
        if (!($node->left instanceof Node\Expr\ErrorSuppress)) {
            $node->left = new Node\Expr\ErrorSuppress($node->left);
        }
        return self::callPoly('coalesce', $node->left, $node->right);
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
        return isset($ifNotNull) ?: $then;
    }
}
