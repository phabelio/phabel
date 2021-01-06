<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Variable;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ArrowClosureVariableFinder extends Plugin
{
    private array $found = [];
    public function enter(Variable $var)
    {
        if (\is_string($var->name) && $var->name !== 'this') {
            $this->found[$var->name]= new ClosureUse($var, $this->getConfig('byRef', false));
        }
    }
    /**
     * Get found closure uses
     *
     * @return array<string, ClosureUse>
     */
    public function getFound(): array
    {
        $found = $this->found;
        $this->found = [];
        return array_values($found);
    }
}
