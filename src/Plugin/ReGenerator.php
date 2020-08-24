<?php

namespace Phabel\Plugin;

use Phabel\Plugin;
use PhpParser\Builder\FunctionLike;

class ReGenerator extends Plugin
{
    const SHOULD_ATTRIBUTE = 'shouldRegenerate';
    public function enter(FunctionLike $function)
    {
        if (!$function->getAttribute(self::SHOULD_ATTRIBUTE, false)) {
            return;
        }
        
    }
    public function runAfter(): array
    {
        return [ArrowClosure::class];
    }
}
