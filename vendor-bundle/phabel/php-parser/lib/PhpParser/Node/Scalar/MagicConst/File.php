<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Scalar\MagicConst;

use PhabelVendor\PhpParser\Node\Scalar\MagicConst;
class File extends MagicConst
{
    /**
     *
     */
    public function getName() : string
    {
        return '__FILE__';
    }
    /**
     *
     */
    public function getType() : string
    {
        return 'Scalar_MagicConst_File';
    }
}
