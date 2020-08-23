<?php

namespace Phabel;

use PhpParser\Node;
use PhpParser\NodeAbstract;

/**
 * Root node.
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
    public function getSubNodeNames(): array
    {
        return ['stmts'];
    }
    public function getType(): string
    {
        return 'rootNode';
    }
}
