<?php

declare (strict_types=1);
namespace PhabelVendor\PhpParser\Node\Stmt;

use PhabelVendor\PhpParser\Node;
abstract class TraitUseAdaptation extends Node\Stmt
{
    /** @var Node\Name|null Trait name */
    public $trait;
    /** @var Node\Identifier Method name */
    public $method;
}
