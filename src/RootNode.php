<?php

namespace Phabel;

use PhabelVendor\PhpParser\Node;
use PhabelVendor\PhpParser\NodeAbstract;
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
    public function getSubNodeNames() : array
    {
        return ['stmts'];
    }
    public function getType() : string
    {
        return 'rootNode';
    }
}
