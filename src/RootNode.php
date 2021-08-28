<?php

namespace Phabel;

use Phabel\PhpParser\Node;
use Phabel\PhpParser\NodeAbstract;
/**
 * Root node.
 *
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class RootNode extends NodeAbstract
{
    /**
     * Children.
     *
     * @var Node[]
     */
    public $stmts = [];
    public function __construct(array $stmts, array $attributes = [])
    {
        $this->stmts = $stmts;
        parent::__construct($attributes);
    }
    public function getSubNodeNames()
    {
        $phabelReturn = ['stmts'];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    public function getType()
    {
        $phabelReturn = 'rootNode';
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (string) $phabelReturn;
        }
        return $phabelReturn;
    }
}
