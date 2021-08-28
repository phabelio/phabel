<?php

namespace Phabel\PhpParser\Node\Expr;

use Phabel\PhpParser\Node\Expr;
class Array_ extends Expr
{
    // For use in "kind" attribute
    const KIND_LONG = 1;
    // array() syntax
    const KIND_SHORT = 2;
    // [] syntax
    /** @var (ArrayItem|null)[] Items */
    public $items;
    /**
     * Constructs an array node.
     *
     * @param (ArrayItem|null)[] $items      Items of the array
     * @param array       $attributes Additional attributes
     */
    public function __construct(array $items = [], array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->items = $items;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['items'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Expr_Array';
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
