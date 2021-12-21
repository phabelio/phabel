<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Expr\AssignOp;

use PhabelVendor\PhpParser\Node\Expr\AssignOp;
class BitwiseXor extends AssignOp
{
    /**
     *
     */
    public function getType() : string
    {
        return 'Expr_AssignOp_BitwiseXor';
    }
}
