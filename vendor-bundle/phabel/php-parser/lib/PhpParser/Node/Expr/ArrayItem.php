<?php

namespace Phabel\PhpParser\Node\Expr;

use Phabel\PhpParser\Node\Expr;
class ArrayItem extends Expr
{
    /** @var null|Expr Key */
    public $key;
    /** @var Expr Value */
    public $value;
    /** @var bool Whether to assign by reference */
    public $byRef;
    /** @var bool Whether to unpack the argument */
    public $unpack;
    /**
     * Constructs an array item node.
     *
     * @param Expr      $value      Value
     * @param null|Expr $key        Key
     * @param bool      $byRef      Whether to assign by reference
     * @param array     $attributes Additional attributes
     */
    public function __construct(Expr $value, Expr $key = null, $byRef = \false, array $attributes = [], $unpack = \false)
    {
        if (!\is_bool($byRef)) {
            if (!(\is_bool($byRef) || \is_numeric($byRef) || \is_string($byRef))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($byRef) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($byRef) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $byRef = (bool) $byRef;
            }
        }
        if (!\is_bool($unpack)) {
            if (!(\is_bool($unpack) || \is_numeric($unpack) || \is_string($unpack))) {
                throw new \TypeError(__METHOD__ . '(): Argument #5 ($unpack) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($unpack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $unpack = (bool) $unpack;
            }
        }
        $this->attributes = $attributes;
        $this->key = $key;
        $this->value = $value;
        $this->byRef = $byRef;
        $this->unpack = $unpack;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['key', 'value', 'byRef', 'unpack'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Expr_ArrayItem';
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
