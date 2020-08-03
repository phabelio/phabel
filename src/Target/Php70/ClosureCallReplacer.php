<?php

namespace Phabel\Target\Php70;

use Phabel\Plugin;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;

class ClosureCallReplacer extends Plugin
{
    /**
     * Replace composite function calls
     *
     * @param FuncCall $node Function call
     * 
     * @return StaticCall|null
     */
    public function enter(FuncCall $node): ?StaticCall
    {
        $name = $node->name;
        if ($name instanceof Name || $name instanceof Variable) {
            return null;
        }
        \array_unshift($node->args, new Arg($name));
        return self::callPoly('callMe', ...$node->args);
    }

    /**
     * Call provided argument.
     *
     * @param callable $callable    Callable
     * @param mixed   ...$arguments Arguments
     *
     * @return mixed
     */
    public static function callMe(callable $callable, ...$arguments)
    {
        return $callable($arguments);
    }
}
