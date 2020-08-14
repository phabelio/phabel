<?php

namespace Phabel;

use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class Context
{
    /**
     * Parent nodes stack
     * 
     * @var SplStack<Node>
     */
    public SplStack $parents;
    public function __construct()
    {
        $this->parents = new SplStack;
    }
}
