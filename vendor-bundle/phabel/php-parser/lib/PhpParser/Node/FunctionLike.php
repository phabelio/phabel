<?php

namespace Phabel\PhpParser\Node;

use Phabel\PhpParser\Node;
interface FunctionLike extends Node
{
    /**
     * Whether to return by reference
     *
     * @return bool
     */
    public function returnsByRef();
    /**
     * List of parameters
     *
     * @return Param[]
     */
    public function getParams();
    /**
     * Get the declared return type or null
     *
     * @return null|Identifier|Name|NullableType|UnionType
     */
    public function getReturnType();
    /**
     * The function body
     *
     * @return Stmt[]|null
     */
    public function getStmts();
    /**
     * Get PHP attribute groups.
     *
     * @return AttributeGroup[]
     */
    public function getAttrGroups();
}
