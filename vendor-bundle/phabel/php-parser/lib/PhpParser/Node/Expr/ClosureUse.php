<?php

namespace Phabel\PhpParser\Node\Expr;

use Phabel\PhpParser\Node\Expr;
class ClosureUse extends Expr
{
    /** @var Expr\Variable Variable to use */
    public $var;
    /** @var bool Whether to use by reference */
    public $byRef;
    /**
     * Constructs a closure use node.
     *
     * @param Expr\Variable $var        Variable to use
     * @param bool          $byRef      Whether to use by reference
     * @param array         $attributes Additional attributes
     */
    public function __construct(Expr\Variable $var, $byRef = \false, array $attributes = [])
    {
        if (!\is_bool($byRef)) {
            if (!(\is_bool($byRef) || \is_numeric($byRef) || \is_string($byRef))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($byRef) must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($byRef) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $byRef = (bool) $byRef;
            }
        }
        $this->attributes = $attributes;
        $this->var = $var;
        $this->byRef = $byRef;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['var', 'byRef'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Expr_ClosureUse';
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
