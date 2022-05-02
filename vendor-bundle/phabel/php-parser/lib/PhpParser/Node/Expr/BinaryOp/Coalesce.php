<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Expr\BinaryOp;

use PhabelVendor\PhpParser\Node\Expr\BinaryOp;
class Coalesce extends BinaryOp
{
    /**
     *
     */
    public function getOperatorSigil() : string
    {
        return '??';
    }
    /**
     *
     */
    public function getType() : string
    {
        return 'Expr_BinaryOp_Coalesce';
    }
}
