<?php

namespace Phabel\PhpParser\Node;

use Phabel\PhpParser\NodeAbstract;
class Param extends NodeAbstract
{
    /** @var null|Identifier|Name|NullableType|UnionType Type declaration */
    public $type;
    /** @var bool Whether parameter is passed by reference */
    public $byRef;
    /** @var bool Whether this is a variadic argument */
    public $variadic;
    /** @var Expr\Variable|Expr\Error Parameter variable */
    public $var;
    /** @var null|Expr Default value */
    public $default;
    /** @var int */
    public $flags;
    /** @var AttributeGroup[] PHP attribute groups */
    public $attrGroups;
    /**
     * Constructs a parameter node.
     *
     * @param Expr\Variable|Expr\Error                           $var        Parameter variable
     * @param null|Expr                                          $default    Default value
     * @param null|string|Identifier|Name|NullableType|UnionType $type       Type declaration
     * @param bool                                               $byRef      Whether is passed by reference
     * @param bool                                               $variadic   Whether this is a variadic argument
     * @param array                                              $attributes Additional attributes
     * @param int                                                $flags      Optional visibility flags
     * @param AttributeGroup[]                                   $attrGroups PHP attribute groups
     */
    public function __construct($var, Expr $default = null, $type = null, $byRef = \false, $variadic = \false, array $attributes = [], $flags = 0, array $attrGroups = [])
    {
        if (!\is_bool($byRef)) {
            if (!(\is_bool($byRef) || \is_numeric($byRef) || \is_string($byRef))) {
                throw new \TypeError(__METHOD__ . '(): Argument #4 ($byRef) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($byRef) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $byRef = (bool) $byRef;
            }
        }
        if (!\is_bool($variadic)) {
            if (!(\is_bool($variadic) || \is_numeric($variadic) || \is_string($variadic))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($variadic) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($variadic) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $variadic = (bool) $variadic;
            }
        }
        if (!\is_int($flags)) {
            if (!(\is_bool($flags) || \is_numeric($flags))) {
                throw new \TypeError(__METHOD__ . '(): Argument #7 ($flags) must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($flags) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $flags = (int) $flags;
            }
        }
        $this->attributes = $attributes;
        $this->type = \is_string($type) ? new Identifier($type) : $type;
        $this->byRef = $byRef;
        $this->variadic = $variadic;
        $this->var = $var;
        $this->default = $default;
        $this->flags = $flags;
        $this->attrGroups = $attrGroups;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['attrGroups', 'flags', 'type', 'byRef', 'variadic', 'var', 'default'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Param';
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
