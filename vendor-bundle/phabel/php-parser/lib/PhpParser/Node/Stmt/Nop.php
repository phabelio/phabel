<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Stmt;

use PhabelVendor\PhpParser\Node;
/** Nop/empty statement (;). */
class Nop extends Node\Stmt
{
    public function getSubNodeNames() : array
    {
        return [];
    }
    public function getType() : string
    {
        return 'Stmt_Nop';
    }
}
