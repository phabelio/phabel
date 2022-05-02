<?php

declare (strict_types=1);
namespace PhabelVendor\PHPStan\PhpDocParser\Ast\ConstExpr;

use PhabelVendor\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ConstExprFalseNode implements ConstExprNode
{
    use NodeAttributes;
    /**
     *
     */
    public function __toString() : string
    {
        return 'false';
    }
}
