<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\UnionType;

/**
 * Replace all usages of a certain type in typehints.
 * 
 * @author Daniil Gentili <daniil@daniil.it>
 */
class TypeHintStripper extends Plugin
{
    /**
     * Strip typehint.
     *
     * @param null|Identifier|Name|NullableType|UnionType &$type Type
     *
     * @return void
     */
    private function strip(?Node &$type): void
    {
        if (!$type || $type instanceof UnionType) {
            return;
        }
        if ($type instanceof NullableType && $this->getConfig('nullable', false)) {
            $type = null;
            return;
        }
        $throwableType = $type instanceof NullableType ? $type->type : $type;
        if (\in_array($throwableType->toString(), $this->getConfig('types', []))) {
            $type = null;
        }
    }
    /**
     * Remove param.
     *
     * @param Param $node Parameter
     * @return void
     */
    public function enterParam(Param $node): void
    {
        $this->strip($node->type);
    }
    /**
     * Strip return throwable type.
     *
     * @param FunctionLike $func Function
     *
     * @return void
     */
    public function enterFuncReturn(FunctionLike $func): void
    {
        $this->strip($func->getReturnType());
    }
}
