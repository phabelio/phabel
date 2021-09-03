<?php

declare (strict_types=1);
namespace Phabel\PhpParser\Node\Expr\AssignOp;

use Phabel\PhpParser\Node\Expr\AssignOp;
class BitwiseAnd extends AssignOp
{
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseAnd';
    }
}
