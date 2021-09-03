<?php

declare (strict_types=1);
namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Node;
abstract class TraitUseAdaptation extends Node\Stmt
{
    /** @var Node\Name|null Trait name */
    public $trait;
    /** @var Node\Identifier Method name */
    public $method;
}
