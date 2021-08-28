<?php

namespace Phabel\PhpParser\Node;

use Phabel\PhpParser\NodeAbstract;
class Arg extends NodeAbstract
{
    /** @var Identifier|null Parameter name (for named parameters) */
    public $name;
    /** @var Expr Value to pass */
    public $value;
    /** @var bool Whether to pass by ref */
    public $byRef;
    /** @var bool Whether to unpack the argument */
    public $unpack;
    /**
     * Constructs a function call argument node.
     *
     * @param Expr  $value      Value to pass
     * @param bool  $byRef      Whether to pass by ref
     * @param bool  $unpack     Whether to unpack the argument
     * @param array $attributes Additional attributes
     * @param Identifier|null $name Parameter name (for named parameters)
     */
    public function __construct(Expr $value, $byRef = \false, $unpack = \false, array $attributes = [], Identifier $name = null)
    {
        if (!\is_bool($byRef)) {
            if (!(\is_bool($byRef) || \is_numeric($byRef) || \is_string($byRef))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($byRef) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($byRef) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $byRef = (bool) $byRef;
            }
        }
        if (!\is_bool($unpack)) {
            if (!(\is_bool($unpack) || \is_numeric($unpack) || \is_string($unpack))) {
                throw new \TypeError(__METHOD__ . '(): Argument #3 ($unpack) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($unpack) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $unpack = (bool) $unpack;
            }
        }
        $this->attributes = $attributes;
        $this->name = $name;
        $this->value = $value;
        $this->byRef = $byRef;
        $this->unpack = $unpack;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['name', 'value', 'byRef', 'unpack'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Arg';
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
