<?php

declare (strict_types=1);
namespace PhabelVendor\PHPStan\PhpDocParser\Ast\Type;

use PhabelVendor\PHPStan\PhpDocParser\Ast\NodeAttributes;
class ThisTypeNode implements TypeNode
{
    use NodeAttributes;
    /**
     *
     */
    public function __toString() : string
    {
        return '$this';
    }
}
