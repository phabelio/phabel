<?php

namespace Phabel\PhpParser\Node\Expr;

use Phabel\PhpParser\Node;
use Phabel\PhpParser\Node\Expr;
use Phabel\PhpParser\Node\FunctionLike;
class ArrowFunction extends Expr implements FunctionLike
{
    /** @var bool */
    public $static;
    /** @var bool */
    public $byRef;
    /** @var Node\Param[] */
    public $params = [];
    /** @var null|Node\Identifier|Node\Name|Node\NullableType|Node\UnionType */
    public $returnType;
    /** @var Expr */
    public $expr;
    /** @var Node\AttributeGroup[] */
    public $attrGroups;
    /**
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'static'     => false   : Whether the closure is static
     *                          'byRef'      => false   : Whether to return by reference
     *                          'params'     => array() : Parameters
     *                          'returnType' => null    : Return type
     *                          'expr'       => Expr    : Expression body
     *                          'attrGroups' => array() : PHP attribute groups
     * @param array $attributes Additional attributes
     */
    public function __construct(array $subNodes = [], array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->static = isset($subNodes['static']) ? $subNodes['static'] : \false;
        $this->byRef = isset($subNodes['byRef']) ? $subNodes['byRef'] : \false;
        $this->params = isset($subNodes['params']) ? $subNodes['params'] : [];
        $returnType = isset($subNodes['returnType']) ? $subNodes['returnType'] : null;
        $this->returnType = \is_string($returnType) ? new Node\Identifier($returnType) : $returnType;
        $this->expr = isset($subNodes['expr']) ? $subNodes['expr'] : null;
        $this->attrGroups = isset($subNodes['attrGroups']) ? $subNodes['attrGroups'] : [];
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['attrGroups', 'static', 'byRef', 'params', 'returnType', 'expr'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function returnsByRef()
    {
        $phabelReturn = $this->byRef;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    public function getParams()
    {
        $phabelReturn = $this->params;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getReturnType()
    {
        return $this->returnType;
    }
    public function getAttrGroups()
    {
        $phabelReturn = $this->attrGroups;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * @return Node\Stmt\Return_[]
     */
    public function getStmts()
    {
        $phabelReturn = [new Node\Stmt\Return_($this->expr)];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Expr_ArrowFunction';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
}
