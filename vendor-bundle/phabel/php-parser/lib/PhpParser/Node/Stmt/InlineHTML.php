<?php

namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Node\Stmt;
class InlineHTML extends Stmt
{
    /** @var string String */
    public $value;
    /**
     * Constructs an inline HTML node.
     *
     * @param string $value      String
     * @param array  $attributes Additional attributes
     */
    public function __construct($value, array $attributes = [])
    {
        if (!\is_string($value)) {
            if (!(\is_string($value) || \is_object($value) && \method_exists($value, '__toString') || (\is_bool($value) || \is_numeric($value)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($value) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($value) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $value = (string) $value;
            }
        }
        $this->attributes = $attributes;
        $this->value = $value;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['value'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Stmt_InlineHTML';
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
