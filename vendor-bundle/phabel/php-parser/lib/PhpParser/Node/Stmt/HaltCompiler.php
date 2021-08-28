<?php

namespace Phabel\PhpParser\Node\Stmt;

use Phabel\PhpParser\Node\Stmt;
class HaltCompiler extends Stmt
{
    /** @var string Remaining text after halt compiler statement. */
    public $remaining;
    /**
     * Constructs a __halt_compiler node.
     *
     * @param string $remaining  Remaining text after halt compiler statement.
     * @param array  $attributes Additional attributes
     */
    public function __construct($remaining, array $attributes = [])
    {
        if (!\is_string($remaining)) {
            if (!(\is_string($remaining) || \is_object($remaining) && \method_exists($remaining, '__toString') || (\is_bool($remaining) || \is_numeric($remaining)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($remaining) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($remaining) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $remaining = (string) $remaining;
            }
        }
        $this->attributes = $attributes;
        $this->remaining = $remaining;
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['remaining'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'Stmt_HaltCompiler';
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
