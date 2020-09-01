<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;

class ArrowClosureVariableFinder extends Plugin
{
    private array $found = [];
    public function enter(Variable $var)
    {
        if ($var->name !== 'this') {
            $this->found[$var->name]= new ClosureUse($var, $this->getConfig('byRef', false));
        }
    }
    public function getFound(): array
    {
        $found = $this->found;
        $this->found = [];
        return $found;
    }
}
