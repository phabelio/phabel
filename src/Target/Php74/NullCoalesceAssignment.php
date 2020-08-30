<?php

namespace Phabel\Target\Php74;

use Phabel\Plugin;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce as BinaryOpCoalesce;

class NullCoalesceAssignment extends Plugin
{
    public function enter(Coalesce $coalesce): Assign
    {
        return new Assign($coalesce->var, new BinaryOpCoalesce($coalesce->var, $coalesce->expr), $coalesce->getAttributes());
    }
}
